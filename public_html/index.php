<?php
    include 'util/combination_util.php';

    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', true);
    ini_set('display_startup_errors', true);

    if (isset($_SESSION['rack'])) {
        unset($_SESSION['rack']);
    }

    if (isset($_SESSION['all'])) {
        unset($_SESSION['all']);
    }

    session_regenerate_id(true);

    $dbhandle = new PDO('sqlite:sys/scrabble.sqlite') or die('Failed to open DB');
    if (!$dbhandle) {
        die($error);
    }

    $dbhandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $statement = $dbhandle->prepare('SELECT rack FROM racks ORDER BY RANDOM() LIMIT 1');
    $statement->execute();

    $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
    $statement = null;

    if (count($result) >= 1) {
        $_SESSION['rack'] = $result[0]['rack'];
    }

    $str_arr = str_split(strtoupper($_SESSION['rack']));

    // Search DB from all possible combinations.
    $combinations = getAllCombinations($str_arr);

    $all = array();

    foreach ($combinations as &$obj) {
        // Avoiding SQL injection attacks
        $statement = $dbhandle->prepare('SELECT words FROM racks WHERE rack = :user_rack');
        $statement->execute([':user_rack' => $obj]);

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement = null;
        if (count($result) >= 1) {
            $possible_words = explode('@@', $result[0]['words']);
            foreach ($possible_words as &$entry) {
                if (!array_key_exists(strlen($entry), $all)) {
                    $all[strlen($entry)] = array();
                }
                $all[strlen($entry)][] = $entry;
            }
        }
    }
    $_SESSION['all'] = $all;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>
      Hello, World!
    </title>
    <!-- Required styles for MDC Web -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css">
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Custom -->
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <div class="mdc-layout__container">
      <header id="word-header" class="mdc-top-app-bar mdc-top-app-bar--prominent">
        <div class="mdc-top-app-bar__row">
          <section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
            <span class="mdc-top-app-bar__title">Text Twist Clone</span></section>
          <section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end">
            <button class="mdc-icon-button material-icons mdc-top-app-bar__action-item--unbounded" aria-label="Email">mail_outline</button>
            <button class="mdc-icon-button material-icons mdc-top-app-bar__action-item--unbounded" aria-label="Code">code</button>
          </section>
        </div>
      </header>
      <div class="mdc-layout-grid mdc-layout__content">
        <div class="mdc-layout-grid__inner">
          <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-12-desktop mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-4-phone">
            <div class="mdc-text-field mdc-text-field--with-trailing-icon text-field mdc-text-field--fullwidth mdc-text-field--no-label mdc-text-field--textarea">
              <input type="text" id="input" class="mdc-text-field__input">
              <i id="enter" class="material-icons mdc-text-field__icon" tabindex="0" role="button">subdirectory_arrow_left</i>
              <div class="mdc-notched-outline">
                <div class="mdc-notched-outline__leading"></div>
                <div class="mdc-notched-outline__notch">
                  <label for="input" class="mdc-floating-label">Create a Word!</label>
                </div>
                <div class="mdc-notched-outline__trailing"></div>
              </div>
            </div>
          </div>
          <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-3-desktop mdc-layout-grid__cell--span-3-tablet mdc-layout-grid__cell--span-1-phone">
            <h2 class="mdc-typography--headline6">Solved</h2>
            <ul id="solved-word-list" class="mdc-list mdc-list--non-interactive mdc-elevation--z10">
            </ul>
          </div>
          <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-9-desktop mdc-layout-grid__cell--span-5-tablet mdc-layout-grid__cell--span-3-phone">
            <h2 class="mdc-typography--headline6"><?php echo 'Letters: '.implode(' ', str_split($_SESSION['rack'])); ?></h2>
            <div class="mdc-card mdc-elevation--z10">
              <div class="mdc-card__primary">
                <h2 class="mdc-card__title mdc-typography mdc-typography--headline6">Possible Words!</h2>
              </div>
              <div class="mdc-card__secondary mdc-typography mdc-typography--body2">
                    <?php
                    foreach (array_keys($_SESSION['all']) as &$key) {
                        echo 'Words with a length of '.$key.' : '.count($_SESSION['all'][$key])."\n<br>";
                    }
                    ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mdc-snackbar">
      <div class="mdc-snackbar__surface">
        <div class="mdc-snackbar__label"
             role="status"
             aria-live="polite">
          
        </div>
        <div class="mdc-snackbar__actions">
          <button class="mdc-icon-button mdc-snackbar__dismiss material-icons" title="Dismiss">close</button>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="/scripts/mdc.js"></script>
    <script type="text/javascript" src="/scripts/submit.js"></script>
  </body>
</html>