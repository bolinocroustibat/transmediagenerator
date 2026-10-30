<?php

	/* STOCKAGE DES PROJETS GENERES (SQLITE) */
	// Le fichier de base n'est pas versionné et est exclu du déploiement (cf. .gitignore et deploy_tag.yml)
	define('DB_FILE', __DIR__.'/data/transmedia.sqlite');

	function db() {
		static $pdo;
		if (isset($pdo)) {
			return $pdo;
		}
		$pdo = new PDO('sqlite:'.DB_FILE);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$pdo->exec('PRAGMA busy_timeout = 5000'); // attend jusqu'à 5 s si une autre requête écrit en même temps
		if ($pdo->query('PRAGMA user_version')->fetchColumn() == 0) {
			db_migrate($pdo);
		}
		return $pdo;
	}

	/* CREATION DU SCHEMA ET IMPORT DES ANCIENS FICHIERS JSON (une seule fois) */
	function db_migrate($pdo) {
		$pdo->exec('BEGIN IMMEDIATE'); // verrouille la base : une seule requête fait la migration
		try {
			if ($pdo->query('PRAGMA user_version')->fetchColumn() == 0) { // re-vérifie une fois le verrou obtenu
				$pdo->exec('CREATE TABLE IF NOT EXISTS projects (
					id INTEGER PRIMARY KEY,
					lang TEXT NOT NULL,
					hash TEXT NOT NULL,
					sentence TEXT NOT NULL,
					created_at TEXT
				)');
				$pdo->exec('CREATE INDEX IF NOT EXISTS projects_lang_hash ON projects (lang, hash)'); // pas UNIQUE : une même phrase donne le même hash
				$legacy_files = array(
					'fr' => __DIR__.'/fr/generated_projects_FR.json',
					'en' => __DIR__.'/en/generated_projects_EN.json',
				);
				$insert = $pdo->prepare('INSERT INTO projects (lang, hash, sentence) VALUES (?, ?, ?)'); // date inconnue pour les anciens projets
				foreach ($legacy_files as $lang => $file) {
					if (!file_exists($file)) {
						continue;
					}
					$table = json_decode(file_get_contents($file), true);
					if (!is_array($table)) {
						throw new Exception('JSON illisible : '.$file);
					}
					foreach ($table as $row_obj) { // chaque entrée est elle-même un objet JSON encodé en chaîne
						$row = json_decode($row_obj, true);
						if (!isset($row['hash'], $row['sentence'])) { // ignore les entrées sans phrase
							continue;
						}
						$insert->execute(array($lang, $row['hash'], $row['sentence']));
					}
				}
				$pdo->exec('PRAGMA user_version = 1');
			}
			$pdo->exec('COMMIT');
		}
		catch (Exception $e) {
			$pdo->exec('ROLLBACK');
			throw $e;
		}
	}

	function projects_add($lang, $hash, $sentence) {
		$stmt = db()->prepare("INSERT INTO projects (lang, hash, sentence, created_at) VALUES (?, ?, ?, datetime('now'))");
		$stmt->execute(array($lang, $hash, $sentence));
	}

	/* RENVOIE LA PHRASE CORRESPONDANT AU HASH (la plus récente en cas de doublon), ou null */
	function projects_find($lang, $hash) {
		$stmt = db()->prepare('SELECT sentence FROM projects WHERE lang = ? AND hash = ? ORDER BY id DESC LIMIT 1');
		$stmt->execute(array($lang, $hash));
		$sentence = $stmt->fetchColumn();
		return $sentence === false ? null : $sentence;
	}

	/* RENVOIE TOUS LES PROJETS, DU PLUS RECENT AU PLUS ANCIEN */
	function projects_all($lang) {
		$stmt = db()->prepare('SELECT hash, sentence FROM projects WHERE lang = ? ORDER BY id DESC');
		$stmt->execute(array($lang));
		return $stmt->fetchAll();
	}

?>
