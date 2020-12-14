<?php
declare(strict_types=1);

// Define basic
define('PHP_V', phpversion());
define('PHP_V_WARN', '7.1');
define('PHP_V_MIN', '7.2');

define('ROOT', __DIR__);
define('MOTEUR', ROOT.'/moteur');
define('DB_DUMP', ROOT.'/mysql/oressource.sql');
define('DB_CONFIG', ROOT.'/moteur/dbconfig.php');
define('DB_CONFIG_EXEMPLE', ROOT.'/moteur/dbconfig.php.example');

// Define msg
define('MSG_HTML_TITLE', '[Étape %d] Installation de Oressource');
define('MSG_HTML_CONTINUE', '<a href="%s%s%d" class="continue"><button type="button" %s>Continuer</button></a>');

define('MSG_HTML_FINAL_TITLE', 'Fin de votre installation');
define('MSG_HTML_FINAL_CONTENT', '<div>Vous pouvez vous connecter au compte Administrateur<br /><i>Par mesure de sécurité, nous vous conseillons de supprimer le fichier `setup.php`</i></div><div class="user"><b>Identifant:</b> %s</div><div class="user"><b>Mot de passe:</b> Il n\'y a que vous à le connaître</div>');
define('MSG_HTML_FINAL_CONTENT_ERROR', 'Ho non ...<br />Il semble y avoir un ou des problemes avec votre installation, ce qui va demander une intervention manuel de votre part.<br />Référez-vous au fichier d\'installation `INSTALL.md` pour la marche a suivre.');

define('MSG_HTML_STEP1_TITLE', 'Verification de l\'instance');
define('MSG_HTML_STEP1_SUB', '<span class="tag--success">%d</span><span class="tag--warning">%d</span><span class="tag--danger">%d</span>');
define('MSG_HTML_STEP1_SUCCESS', 'Réussite: ');
define('MSG_HTML_STEP1_WARNING', 'Mises en gardes: ');
define('MSG_HTML_STEP1_ERRORS', 'Problèmes critiques: ');

define('MSG_HTML_STEP2_TITLE', 'Configuration de la base de donnée');
define('MSG_HTML_STEP2_SUB', 'Merci de founir les informations de votre base de donnée, dans le formulaire ci dessous.');
define('MSG_HTML_STEP2_DB_ERROR', 'Can\'t establishing a database connection, check user name and password, host name and if the database is running.');
define('MSG_HTML_STEP2_FORM_HOST', 'Serveur MySql');
define('MSG_HTML_STEP2_FORM_HOST_SUB', 'Adresse du serveur de la base de données MySql. Par defaut `localhost`');
define('MSG_HTML_STEP2_FORM_USR', 'Utilisateur');
define('MSG_HTML_STEP2_FORM_USR_SUB', 'Nom de l\'utilisateur de la base de données');
define('MSG_HTML_STEP2_FORM_PWD', 'Mot de passe');
define('MSG_HTML_STEP2_FORM_PWD_SUB', 'Mot de passe de l\'utilisateur');
define('MSG_HTML_STEP2_FORM_DBN', 'Base de données');
define('MSG_HTML_STEP2_FORM_DBN_SUB', 'Nom de la base de données que devra utiliser Oressource');
define('MSG_HTML_STEP2_FORM_ERROR', 'Le champ `%s` ne peut pas etre vide');

define('MSG_HTML_STEP3_TITLE', 'Configuration de l\'Administrateur');
define('MSG_HTML_STEP3_SUB', 'Pour configurer votre compte administrateur, merci de fournir les information ce dessous.');
define('MSG_HTML_STEP3_FORM_NAME', 'Utilisateur');
define('MSG_HTML_STEP3_FORM_NAME_SUB', 'Le nom de l\'utilisateur est toujours `Admin`, ceci defini un presnom');
define('MSG_HTML_STEP3_FORM_PASS', 'Mot de passe');
define('MSG_HTML_STEP3_FORM_PASS_SUB', 'Doit contenir une minuscule, une majuscule, un nombre, un caractère spécial et avoir au moins une taille de 8 caractères');
define('MSG_HTML_STEP3_FORM_PASS_CONFIRM', 'Confirmer le mot de passe');
define('MSG_HTML_STEP3_FORM_PASS_ERROR', 'Le mot de passe et sa confirmation ne semblent pas identiques');
define('MSG_HTML_STEP3_FORM_EMAIL', 'E-mail');
define('MSG_HTML_STEP3_FORM_EMAIL_SUB', 'l\'email de l\'administrateur');
define('MSG_HTML_STEP3_FORM_EMAIL_ERROR', 'Votre email semble mal formé');
define('MSG_HTML_STEP3_FORM_ERROR', 'Le champ `%s` ne peut pas etre vide');

define('MSG_REQUIRE_PHP', 'Votre version de PHP %s est prise en charge');
define('MSG_REQUIRE_PDO', 'PHP `pdo_mysql` est installé et chargé');
define('MSG_REQUIRE_MYSQLI', 'PHP `mysqli` est installé et chargé');
define('MSG_REQUIRE_TZONE', 'Timezone est défini à %s');
define('MSG_REQUIRE_MOTEURFOLDER', 'Nous pouvons écrire le fichier de configuration');
define('MSG_REQUIRE_CONFEX', 'Nous pouvons lire le fichier d\'exemple de configuration');
define('MSG_REQUIRE_DBDUMP', 'Nous pouvons lire le modèle de base de données');

define('MSG_REQUIRE_WARN_PHP', 'Vous utilisez une version ancienne et non sécurisée de PHP %s. Veuillez envisager de la mettre à jour');
define('MSG_REQUIRE_WARN_TZONE', 'Configuration `date.timezone` dans `php.ini` est vide');

define('MSG_REQUIRE_ERROR_PHP', 'Votre PHP %s est trop ancien et non sécurisé. Une mise à jour PHP supérieure à %s est requise pour Oressource');
define('MSG_REQUIRE_ERROR_PDO', 'Vous devez installer `pdo_mysql` pour PHP');
define('MSG_REQUIRE_ERROR_MYSQLI', 'Vous devez installer `mysqli` pour PHP');
define('MSG_REQUIRE_ERROR_MOTEURFOLDER', 'Impossible d \' écrire le fichier de configuration `%s`');
define('MSG_REQUIRE_ERROR_CONFEX', 'Impossible de lire le fichier d\'exemple `%s`');
define('MSG_REQUIRE_ERROR_DBDUMP', 'Impossible de lire le modèle de base de données `%s`');

define('MSG_SET_ERROR_DB_DUMP', 'Erreur critique lors de la lecture du fichier base de données');
define('MSG_SET_ERROR_DB_INSERT', 'Impossible d\'insérer le modèle de base de données');
define('MSG_SET_ERROR_CONFIG_DUMP', 'Erreur critique lors de la lecture du fichier d\'exemple de configuration');
define('MSG_SET_ERROR_CONFIG_SET', 'Echec de l \' écriture du fichier de configuration');

// Define msg log
define('MSG_SETUP_END', '[INFO] La configuration est terminée, profitez bien de votre oressource');
define('MSG_ENV_ERROR', '[ERROR] La variable d\'environnement `%s` est vide ou n\'existe pas');
define('MSG_CONFIG_FILE_EXIST', '[ERROR] Le fichier `'.DB_CONFIG.'` existe déjà');
define('MSG_DB_CONNECT_ERROR', '[ERROR] Impossible de ce connecter à la base de données, vérifiez vos variables d\'environnement et si la base de données est en cours d\'exécution');
define('MSG_ADMIN_EMAIL_ERROR', '[ERROR] Format de courrier électronique incorrect `%s`');
define('MSG_SQLINSERT_ERROR_CONNECT', '[ERROR] %s');
define('MSG_SQLINSERT_ERROR_EXEC', '[ERROR] Impossible d\'exécuter la requête SQL:\n%s');

// MAIN
function main_cli(array $config)
{
    $status = array(
        'success' => array(),
        'warning' => array(),
        'errors' => array()
    );

    // Check && Load ENV var
    load_env_cli($config, $status['errors']);

    // Check requirement
    required_conf($status);
    required_mode($status);

    // Show config info
    array_to_cli_list($status['warning'], MSG_HTML_STEP1_WARNING, 'warning');
    if (! empty($status['errors'])) {
        array_to_cli_list($status['errors'], MSG_HTML_STEP1_ERRORS, 'errors');
        die(1);
    }

    // Check db connection
    if (! db_is_reachable($config['DB'])) {
        error_log(MSG_DB_CONNECT_ERROR);
        die(1);
    }

    // Check admin config
    if (! filter_var($config['USER']['EMAIL'], FILTER_VALIDATE_EMAIL) !== false) {
        error_log(sprintf(MSG_ADMIN_EMAIL_ERROR, $config['USER']['EMAIL']));
        die(1);
    }

    // Set oressource
    set_oressource_db($config['DB'], $status['errors']);
    set_oressource_admin($config['DB'], $config['USER'], $status['errors']);
    set_oressource_config_file($config['DB'], $status['errors']);
    if (! empty($status['errors'])) {
        array_to_cli_list($status['errors'], MSG_HTML_STEP1_ERRORS, 'errors');
        die(1);
    }

    // End
    error_log(MSG_SETUP_END);
    die(0);
}

function main(array $session_init): void
{
    // Session start
    session_start();
    if (empty($_SESSION)) {
        $_SESSION['STEP'] = 1;
        $_SESSION['USER'] = $session_init['USER'];
        $_SESSION['DB'] = $session_init['DB'];
    }

    $body = '';
    $step = step();
    $status = array(
        'success' => array(),
        'warning' => array(),
        'errors' => array()
    );

    // Redirect to force validate all step
    if ($_SESSION['STEP'] < $step) {
        header('HTTP/1.1 302 Found');
        header('Location: '.location_step($_SESSION['STEP']));
        header('Connection: close');
        die;
    }

    // Step 1: Check server configuration
    // Step 2: Set && Check sql info
    // Step 3: Set && Check admin user info
    // Step 4: Set dbconfig && Insert db data
    switch ($step) {
        case '2':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                db_validate($status['errors']);

                if (
                    empty($status['errors']) &&
                    db_is_reachable($_SESSION['DB'])
                ) {
                    $_SESSION['STEP'] = 3;
                    goto_step($_SESSION['STEP']);
                } else {
                    $status['errors'][] = MSG_HTML_STEP2_DB_ERROR;
                }
            }

            db_html_form(
                $body,
                array_to_html_list(
                    $status['errors'],
                    MSG_HTML_STEP1_ERRORS,
                    'errors'
                )
            );
            break;
        case '3':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                user_validate($status['errors']);

                if ($status['errors'] === array()) {
                    $_SESSION['STEP'] = 4;
                    goto_step($_SESSION['STEP']);
                }
            }

            user_html_form(
                $body,
                array_to_html_list(
                    $status['errors'],
                    MSG_HTML_STEP1_ERRORS,
                    'errors'
                )
            );
            break;
        case '4':
            set_oressource_db($_SESSION['DB'], $status['errors']);
            set_oressource_admin($_SESSION['DB'], $_SESSION['USER'], $status['errors']);
            set_oressource_config_file($_SESSION['DB'], $status['errors']);

            final_html_result(
                $body,
                array_to_html_list(
                    $status['errors'],
                    MSG_HTML_STEP1_ERRORS,
                    'errors'
                )
            );
            break;
        case '1':
        default:
            required_conf($status);
            required_mode($status);

            // If no error permite access to the next step
            if ($status['errors'] === array()) {
                $_SESSION['STEP'] = 2;
            }

            status_to_html($body, $status, $step);
            break;
    }

    setup_header($step);
    setup_body($body);
    setup_footer();
}

function step(): int
{
    if (! isset($_GET['step'])) {
        return 1;
    }

    return (int) $_GET['step'];
}

function location_step(int $step): string
{
    return sprintf(
        '%s?step=%s',
        parse_url(
            $_SERVER['REQUEST_URI'],
            PHP_URL_PATH
        ),
        $step
    );
}

function goto_step(int $step): void
{
    header('HTTP/1.1 302 Found');
    header('Location: '.location_step($step));
    header('Connection: close');

    die;
}

// FUNCTION HTML/CSS
function setup_header(int $step): void
{
    $title = sprintf(MSG_HTML_TITLE, $step);
    header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="noindex,nofollow" />
    <title><?php echo $title ?></title>
    <?php setup_css() ?>
</head>
<body>
    <h1><?php echo $title ?></h1>
    <?php
}

function setup_body(string $content): void
{
    ?>
    <main>
        <article class="box">
        <?php echo $content ?>
        </article>
    </main>
    <?php
}

function setup_footer(): void
{
    ?>
</body>
</html>
    <?php
}

function setup_css(): void
{
    ?>
    <style>
        :after,:before{-webkit-box-sizing:border-box;box-sizing:border-box}html{font-family:sans-serif;line-height:1.15;-webkit-text-size-adjust:100%;-moz-text-size-adjust:100%;-ms-text-size-adjust:100%;text-size-adjust:100%;-ms-overflow-style:scrollbar;-webkit-tap-highlight-color:transparent}@-ms-viewport{width:device-width}@-o-viewport{width:device-width}@viewport{width:device-width}article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}body{font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol;font-size:1rem;font-weight:400;text-align:left}[tabindex="-1"]:focus{outline:0!important}h1,h2,h3,h4,h5,h6{margin-top:0;margin-bottom:.5rem}p{margin-top:0;margin-bottom:1rem}abbr[data-original-title],abbr[title]{text-decoration:underline;-webkit-text-decoration:underline dotted;text-decoration:underline dotted;cursor:help;border-bottom:0}address,dl,ol,ul{margin-bottom:1rem}dl,ol,ul{margin-top:0}ol ol,ol ul,ul ol,ul ul{margin-bottom:0}b,strong{font-weight:700}small{font-size:80%}a{color:#007bff;text-decoration:none;background-color:transparent}a:hover{color:#0056b3;text-decoration:underline}a:not([href]):not([tabindex]),a:not([href]):not([tabindex]):focus,a:not([href]):not([tabindex]):hover{color:inherit;text-decoration:none}a:not([href]):not([tabindex]):focus{outline:0}[role=button],a,area,button,input:not([type=range]),label,select,summary,textarea{-ms-touch-action:manipulation;touch-action:manipulation}table{border-collapse:collapse}caption{padding-top:.75rem;padding-bottom:.75rem;color:#868e96;text-align:left;caption-side:bottom}th{text-align:inherit}label{margin-bottom:.5rem}button{border-radius:0}button:focus{outline:1px dotted;outline:5px auto -webkit-focus-ring-color}button,input,optgroup,select,textarea{margin:0;font-family:inherit;font-size:inherit;line-height:inherit}button,input{overflow:visible}button,select{text-transform:none}[type=reset],[type=submit],button,html [type=button]{-webkit-appearance:button;-moz-appearance:button;appearance:button}[type=button]::-moz-focus-inner,[type=reset]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{padding:0;border-style:none}input[type=checkbox],input[type=radio]{-webkit-box-sizing:border-box;box-sizing:border-box;padding:0}input[type=date],input[type=datetime-local],input[type=month],input[type=time]{-webkit-appearance:listbox;-moz-appearance:listbox;appearance:listbox}textarea{overflow:auto}fieldset{min-width:0;padding:0;margin:0;border:0}legend{display:block;width:100%;max-width:100%;padding:0;margin-bottom:.5rem;font-size:1.5rem;line-height:inherit;color:inherit;white-space:normal}progress{vertical-align:baseline}[type=number]::-webkit-inner-spin-button,[type=number]::-webkit-outer-spin-button{height:auto}[type=search]{outline-offset:-2px;-webkit-appearance:none;-moz-appearance:none;appearance:none}[type=search]::-webkit-search-cancel-button,[type=search]::-webkit-search-decoration{-webkit-appearance:none;appearance:none}::-webkit-file-upload-button{font:inherit;-webkit-appearance:button;appearance:button}[hidden]{display:none!important}html{-webkit-box-sizing:border-box;box-sizing:border-box}*,:after,:before{-webkit-box-sizing:inherit;box-sizing:inherit;min-width:0;min-height:0}html{font-size:62.5%;font-size:.625em}body{margin:0;font-size:1.4rem;background-color:#fff;color:#212529;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen-Sans,Ubuntu,Cantarell,Helvetica Neue,sans-serif;line-height:1.5}@media (min-width:576px){body{font-size:1.6rem}}a{color:#333;text-decoration:underline}a:active,a:focus,a:hover{color:#0d0d0d;text-decoration:underline}.h1-like,h1{font-size:2.8rem;font-family:sans-serif;font-weight:500}@media (min-width:576px){.h1-like,h1{font-size:3.2rem}}.h2-like,h2{font-size:2.4rem;font-family:sans-serif;font-weight:500}@media (min-width:576px){.h2-like,h2{font-size:2.8rem}}.h3-like,h3{font-size:2rem;font-weight:500}@media (min-width:576px){.h3-like,h3{font-size:2.4rem}}.h4-like,h4{font-size:1.8rem;font-weight:500}@media (min-width:576px){.h4-like,h4{font-size:2rem}}.h5-like,h5{font-size:1.6rem;font-weight:500}@media (min-width:576px){.h5-like,h5{font-size:1.8rem}}.h6-like,h6{font-size:1.4rem;font-weight:500}@media (min-width:576px){.h6-like,h6{font-size:1.6rem}}dd,h1,h2,h3,h4,h5,h6{margin-top:0;margin-bottom:.5rem}address,blockquote,dl,ol,p,pre,ul{margin-top:0;margin-bottom:1rem}li ol,li p,li ul,ol ol,ul ul{margin-top:0;margin-bottom:0}blockquote,code,img,input,pre,svg,table,td,textarea,video{max-width:100%}ol,ul{padding-left:2em}
        .tag,.tag--danger,.tag--ghost,.tag--info,.tag--inverse,.tag--primary,.tag--success,.tag--warning{display:inline-block;padding:3px .5rem;vertical-align:baseline;white-space:nowrap;color:#212529;border-radius:0;background-color:#e7e9ed;line-height:1}.tag--primary{background-color:#0275d8}.tag--primary,.tag--success{color:#fff;-webkit-box-shadow:none;box-shadow:none}.tag--success{background-color:#5cb85c}.tag--info{background-color:#5bc0de}.tag--info,.tag--warning{color:#000;-webkit-box-shadow:none;box-shadow:none}.tag--warning{background-color:#f0ad4e}.tag--danger{background-color:#d9534f}.tag--danger,.tag--inverse{color:#fff;-webkit-box-shadow:none;box-shadow:none}.tag--inverse{background-color:#333}.tag--ghost{background-color:transparent;color:#fff;-webkit-box-shadow:0 0 0 1px #fff inset;box-shadow:inset 0 0 0 1px #fff}.tag--small{font-size:1.2rem}.tag--big{font-size:2rem}.tag--block{width:100%!important;display:block}.disabled.tag--danger,.disabled.tag--ghost,.disabled.tag--info,.disabled.tag--inverse,.disabled.tag--primary,.disabled.tag--success,.disabled.tag--warning,.tag--disabled,.tag.disabled{opacity:.5;cursor:not-allowed}.tag--danger:empty,.tag--ghost:empty,.tag--info:empty,.tag--inverse:empty,.tag--primary:empty,.tag--success:empty,.tag--warning:empty,.tag:empty{display:none}.btn,.btn--danger,.btn--ghost,.btn--info,.btn--inverse,.btn--primary,.btn--success,.btn--warning,.button,.button--danger,.button--ghost,.button--info,.button--inverse,.button--primary,.button--success,.button--warning,[type=button],button{display:inline-block;padding:1rem 1.5rem;cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;-webkit-transition:.25s;transition:.25s;-webkit-transition-property:background-color,color,border,-webkit-box-shadow;transition-property:background-color,color,border,-webkit-box-shadow;transition-property:box-shadow,background-color,color,border;transition-property:box-shadow,background-color,color,border,-webkit-box-shadow;text-align:center;vertical-align:middle;white-space:nowrap;text-decoration:none;color:#212529;border:none;border-radius:0;background-color:#e7e9ed;font-family:inherit;font-size:inherit;line-height:1}.btn:focus,.button:focus,[type=button]:focus,button:focus{-webkit-tap-highlight-color:transparent}
    </style>
    <style>
        h1 {
            border-bottom: 1px solid;
        }
        h1, h2, #total, .sub {
            text-align: center;
        }
        main {
            width: max-content;
            margin: auto;
            position: relative;
            min-width: 50%;
        }
        #total span {
            margin: 1em;
            padding: .5em;
        }
        #total span::before {
            font-weight: bold;
            padding-left: .2em;
            padding-right: .5em;
        }
        .tag--success::before {content: "✓";}
        .tag--warning::before {content: "!";}
        .tag--danger::before  {content: "✕";}
        .continue {
            right: 0;
            bottom: -5em;
            position: absolute;
        }
        label, span, .tag {
            display: block;
        }
        label {
            padding: 1.5em 0;
        }
        .tag {
            margin-top: .5em;
            font-style: italic;
        }
        input {
            display: block;
        }
        .user {
            padding-top: 1em;
        }
    </style>
    <?php
}

function final_html_result(string &$body, string $error): void
{
    $content = '';
    if ($error === '') {
        $content = sprintf(MSG_HTML_FINAL_CONTENT, $_SESSION['USER']['EMAIL']);
    } else {
        $content = MSG_HTML_FINAL_CONTENT_ERROR;
    }

    $body .= sprintf(
        '<h2>%s</h2><div>%s</div><div>%s</div>',
        MSG_HTML_FINAL_TITLE,
        $content,
        $error
    );
}

function array_to_html_list(array $arr, string $title, string $class): string
{
    $arrLen = count($arr);

    if ($arrLen < 1) {
        return '';
    }

    $arrHtml = sprintf(
        '<h3 id="array-list-%s"><a href="#array-list-%s">[%d] %s</a></h3><ul class="array-list">',
        $class,
        $class,
        $arrLen,
        $title
    );

    foreach ($arr as $i => $v) {
        $arrHtml = sprintf(
            '%s<li class"%s %s"><span>%s</span></li>',
            $arrHtml,
            $class,
            $i,
            $v
        );
    }

    return $arrHtml.'</ul>';
}

function status_to_html(string &$body, array $status, int $step): void
{
    $button = 'enabled';
    $sCount = array(
        'success' => count($status['success']),
        'warning' => count($status['warning']),
        'errors' => count($status['errors'])
    );

    // Disable next button if an error exist
    if ($sCount['errors'] !== 0) {
        $button = 'disabled';
    }

    $body .= sprintf(
        '<h2>%s</h2><div id="total">%s</div>%s%s%s',
        MSG_HTML_STEP1_TITLE,
        sprintf(
            MSG_HTML_STEP1_SUB,
            $sCount['success'],
            $sCount['warning'],
            $sCount['errors']
        ),
        array_to_html_list($status['success'], MSG_HTML_STEP1_SUCCESS, 'success'),
        array_to_html_list($status['warning'], MSG_HTML_STEP1_WARNING, 'warning'),
        array_to_html_list($status['errors'], MSG_HTML_STEP1_ERRORS, 'errors')
    );

    $body .= sprintf(
        MSG_HTML_CONTINUE,
        parse_url(
            $_SERVER['REQUEST_URI'],
            PHP_URL_PATH
        ),
        '?step=',
        ++$step,
        $button
    );
}

function db_html_form(string &$body, string $error_list): void
{
    $body .= sprintf(
        '<h2>%s</h2>
        <span class="sub">%s</span>
        <div>%s</div>
        <form action="" method="post">
            %s
            %s
            %s
            %s

            <input type="submit" value="Continuer" class="continue">
        </form>',
        MSG_HTML_STEP2_TITLE,
        MSG_HTML_STEP2_SUB,
        $error_list,
        html_input('HOST', 'text', MSG_HTML_STEP2_FORM_HOST, MSG_HTML_STEP2_FORM_HOST_SUB, $_SESSION['DB']['HOST']),
        html_input('USER', 'text', MSG_HTML_STEP2_FORM_USR, MSG_HTML_STEP2_FORM_USR_SUB, $_SESSION['DB']['USER']),
        html_input('PASS', 'password', MSG_HTML_STEP2_FORM_PWD, MSG_HTML_STEP2_FORM_PWD_SUB, $_SESSION['DB']['PASS']),
        html_input('NAME', 'text', MSG_HTML_STEP2_FORM_DBN, MSG_HTML_STEP2_FORM_DBN_SUB, $_SESSION['DB']['NAME'])
    );
}

function user_html_form(string &$body, string $error_list): void
{
    $body .= sprintf(
        '<h2>%s</h2>
        <span class="sub">%s</span>
        <div>%s</div>
        <form action="" method="post">
            %s
            %s
            %s
            %s

            <input type="submit" value="Continuer" class="continue">
        </form>',
        MSG_HTML_STEP3_TITLE,
        MSG_HTML_STEP3_SUB,
        $error_list,
        html_input('NAME', 'text', MSG_HTML_STEP3_FORM_NAME, MSG_HTML_STEP3_FORM_NAME_SUB, $_SESSION['USER']['NAME']),
        html_input('PASS', 'password" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$', MSG_HTML_STEP3_FORM_PASS, MSG_HTML_STEP3_FORM_PASS_SUB, ''),
        html_input('PASS_VAL', 'password', MSG_HTML_STEP3_FORM_PASS_CONFIRM, '', ''),
        html_input('EMAIL', 'email', MSG_HTML_STEP3_FORM_EMAIL, MSG_HTML_STEP3_FORM_EMAIL_SUB, $_SESSION['USER']['EMAIL']),
    );
}

function html_input(string $id, string $type, string $title, string $help, string $userValue): string
{
    return sprintf(
        '<label for="%s">%s :
            <input type="%s" id="%s" name="%s" value="%s" placeholder="%s" required>
            <span class="tag">%s</span>
        </label>',
        $id,
        $title,
        $type,
        $id,
        $id,
        $userValue,
        $title,
        $help
    );
}

// FUNCTION CLI
function array_to_cli_list(array $arr, string $title, string $class): void
{
    if (empty($arr)) {
        return;
    }

    error_log(
        sprintf(
            '[%s] %s ',
            $class,
            $title
        )
    );

    foreach ($arr as &$v) {
        error_log('-- '.$v);
    }
}

function load_env_cli(array &$config, array &$error)
{
    // Load DB config
    $config['DB']['HOST'] = get_env_cli('_or_db_host', $error);
    $config['DB']['USER'] = get_env_cli('_or_db_user', $error);
    $config['DB']['PASS'] = get_env_cli('_or_db_pass', $error);
    $config['DB']['NAME'] = get_env_cli('_or_db_name', $error);

    // Load Admin config
    $config['USER']['NAME']  = get_env_cli('_or_user_name', $error);
    $config['USER']['EMAIL'] = get_env_cli('_or_user_email', $error);
    $config['USER']['PASS']  = md5(get_env_cli('_or_user_pass', $error));
}

function get_env_cli(string $name, array &$error): string
{
    $buff = getenv($name);

    if ($buff === false || $buff === '') {
        $error[] = sprintf(MSG_ENV_ERROR, $name);
        return '';
    }

    return $buff;
}

// FUNCTION Setup
function required_conf(array &$status): void
{
    if (version_compare(PHP_V, PHP_V_MIN, '>=')) {
        $status['success']['PHP'] = sprintf(MSG_REQUIRE_PHP, PHP_V);
    } elseif (version_compare(PHP_V, PHP_V_WARN, '>=')) {
        $status['warning']['PHP'] = sprintf(MSG_REQUIRE_WARN_PHP, PHP_V);
    } else {
        $status['errors']['PHP'] = sprintf(MSG_REQUIRE_ERROR_PHP, PHP_V, PHP_V_MIN);
    }

    if (extension_loaded('pdo_mysql')) {
        $status['success']['PDO'] = MSG_REQUIRE_PDO;
    } else {
        $status['errors']['PDO'] = MSG_REQUIRE_ERROR_PDO;
    }

    if (extension_loaded('mysqli')) {
        $status['success']['MYSQLI'] = MSG_REQUIRE_MYSQLI;
    } else {
        $status['errors']['MYSQLI'] = MSG_REQUIRE_ERROR_MYSQLI;
    }

    if (ini_get('date.timezone') === '') {
        $status['warning']['TIMEZONE'] = MSG_REQUIRE_WARN_TZONE;
    } else {
        $status['success']['TIMEZONE'] = sprintf(MSG_REQUIRE_TZONE, ini_get('date.timezone'));
    }
}

function required_mode(array &$status): void
{
    if (is_writable(MOTEUR)) {
        $status['success']['MOTEURFOLDER'] = MSG_REQUIRE_MOTEURFOLDER;
    } else {
        $status['errors']['MOTEURFOLDER'] = sprintf(MSG_REQUIRE_ERROR_MOTEURFOLDER, MOTEUR);
    }

    if (is_readable(DB_CONFIG_EXEMPLE)) {
        $status['success']['CONFIG_EXEMPLE'] = MSG_REQUIRE_CONFEX;
    } else {
        $status['errors']['CONFIG_EXEMPLE'] = sprintf(MSG_REQUIRE_ERROR_CONFEX, DB_CONFIG_EXEMPLE);
    }

    if (is_readable(DB_DUMP)) {
        $status['success']['DB_DUMP'] = MSG_REQUIRE_DBDUMP;
    } else {
        $status['errors']['DB_DUMP'] = sprintf(MSG_REQUIRE_ERROR_DBDUMP, DB_DUMP);
    }
}

function db_validate(array &$error): void
{
    if (isset($_POST['HOST'])) {
        $_SESSION['DB']['HOST'] = stripslashes($_POST['HOST']);
    } else {
        $error[] = sprintf(MSG_HTML_STEP2_FORM_ERROR, MSG_HTML_STEP2_FORM_HOST);
    }

    if (isset($_POST['USER'])) {
        $_SESSION['DB']['USER'] = stripslashes($_POST['USER']);
    } else {
        $error[] = sprintf(MSG_HTML_STEP2_FORM_ERROR, MSG_HTML_STEP2_FORM_USR);
    }

    if (isset($_POST['PASS'])) {
        $_SESSION['DB']['PASS'] = stripslashes($_POST['PASS']);
    } else {
        $error[] = sprintf(MSG_HTML_STEP2_FORM_ERROR, MSG_HTML_STEP2_FORM_PWD);
    }

    if (isset($_POST['NAME'])) {
        $_SESSION['DB']['NAME'] = stripslashes($_POST['NAME']);
    } else {
        $error[] = sprintf(MSG_HTML_STEP2_FORM_ERROR, MSG_HTML_STEP2_FORM_DBN);
    }
}

function user_validate(array &$error): void
{
    if (isset($_POST['NAME'])) {
        $_SESSION['USER']['NAME'] = stripslashes($_POST['NAME']);
    } else {
        $error[] = sprintf(MSG_HTML_STEP3_FORM_ERROR, MSG_HTML_STEP3_FORM_NAME);
    }

    if (isset($_POST['EMAIL'])) {
        if (filter_var($_POST['EMAIL'], FILTER_VALIDATE_EMAIL) !== false) {
            $_SESSION['USER']['EMAIL'] = $_POST['EMAIL'];
        } else {
            $error[] = MSG_HTML_STEP3_FORM_EMAIL_ERROR;
        }
    } else {
        $error[] = sprintf(MSG_HTML_STEP3_FORM_ERROR, MSG_HTML_STEP3_FORM_EMAIL);
    }

    if ((isset($_POST['PASS']) && $_POST['PASS'] !== '') && isset($_POST['PASS_VAL'])) {
        if ($_POST['PASS'] === $_POST['PASS_VAL']) {
            $_SESSION['USER']['PASS'] = md5($_POST['PASS']);
        } else {
            $error[] = MSG_HTML_STEP3_FORM_PASS_ERROR;
        }
    } else {
        $error[] = sprintf(MSG_HTML_STEP3_FORM_ERROR, MSG_HTML_STEP3_FORM_PASS);
    }
}

function set_oressource_db(array $db, array &$error): void
{
    $db_dump = file_get_contents(DB_DUMP);

    if ($db_dump === false) {
        $error[] = MSG_SET_ERROR_DB_DUMP;
        return;
    }

    if (! insert_sql_dump($db_dump, $db)) {
        $error[] = MSG_SET_ERROR_DB_INSERT;
    }
}

function set_oressource_admin(array $db, array $user, array &$error): void
{
    $query = 'UPDATE `utilisateurs` SET `prenom`=:user, `mail`=:mail, `pass`=:pass WHERE `id`=1';

    try {
        $pdo = new PDO(
            'mysql:host='.$db['HOST'].';dbname='.$db['NAME'],
            $db['USER'],
            $db['PASS']
        );
    } catch (PDOException $ex) {
        $error[] = sprintf(MSG_SQLINSERT_ERROR_CONNECT, $ex);
        return;
    }

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user', $user['NAME']);
    $stmt->bindParam(':mail', $user['EMAIL']);
    $stmt->bindParam(':pass', $user['PASS']);

    if ($stmt->execute() === false) {
        $error[] = sprintf(MSG_SQLINSERT_ERROR_EXEC, $query);
    }

    unset($pdo);
}

function set_oressource_config_file(array $db, array &$error): void
{
    $config_dump = file_get_contents(DB_CONFIG_EXEMPLE);

    if ($config_dump === false) {
        $error[] = MSG_SET_ERROR_CONFIG_DUMP;
        return;
    }

    $host = explode(':', $db['HOST']);
    if (count($host) !== 2) {
        $host[1] = 3306;
    }

    $search = array(
        '$host = \'localhost\'',
        '$port = 3306',
        '$base = \'oressource\'',
        '$user = \'oressource\'',
        '$pass = \'hello\''
    );
    $replace = array(
        '$host = \''.$host[0].'\'',
        '$port = '.$host[1].'',
        '$base = \''.$db['NAME'].'\'',
        '$user = \''.$db['USER'].'\'',
        '$pass = \''.$db['PASS'].'\''
    );
    $config_dump = str_replace(
        $search,
        $replace,
        $config_dump
    );

    if (file_put_contents(DB_CONFIG, $config_dump) === false) {
        $error[] = MSG_SET_ERROR_CONFIG_SET;
    }
}

// FUNCTION SQL
function insert_sql_dump(string $dump, array $db): bool
{
    $query = '';

    // Connect DB
    try {
        $pdo = new PDO(
            'mysql:host='.$db['HOST'].';dbname='.$db['NAME'],
            $db['USER'],
            $db['PASS']
        );
    } catch (PDOException $ex) {
        error_log(sprintf(MSG_SQLINSERT_ERROR_CONNECT, $ex));
        return false;
    }

    foreach (explode(PHP_EOL, $dump) as $line) {
        // Commentary or empty line
        if (
            $line === '' ||
            substr($line, 0, 2) === '--'
        ) {
            continue;
        }

        // Append line to query
        $query .= $line;

        // Execute query
        if (substr(trim($line), -1, 1) !== ';') {
            continue;
        }

        if ($pdo->exec($query) === false) {
            error_log(sprintf(MSG_SQLINSERT_ERROR_EXEC, $query));

            unset($pdo);
            return false;
        }

        $query = '';
    }

    // Disconect DB
    unset($pdo);
    return true;
}

function db_is_reachable(array $db): bool
{
    try {
        $pdo = new PDO(
            'mysql:host='.$db['HOST'].';dbname='.$db['NAME'],
            $db['USER'],
            $db['PASS'],
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    } catch (PDOException $ex) {
        return false;
    }

    unset($pdo);
    return true;
}

$config_init = array(
    'USER' => array(
        'NAME' => '',
        'EMAIL' => '',
        'PASS' => ''
    ),
    'DB' => array(
        'HOST' => 'localhost',
        'USER' => '',
        'PASS' => '',
        'NAME' => ''
    )
);

// Start setup
if (php_sapi_name() === 'cli') {
    // Die if config file exist
    if (file_exists(DB_CONFIG)) {
        error_log(MSG_CONFIG_FILE_EXIST);
        die(2);
    }

    main_cli($config_init);
} else {
    // Redirect if config file exist
    if (file_exists(DB_CONFIG)) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: /ifaces/');
        header('Connection: close');
        die;
    }
    
    main($config_init);
}
