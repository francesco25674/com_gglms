<?php

error_reporting(E_ALL & ~E_NOTICE);
defined('_JEXEC') or die('Restricted access');
define('SMARTY_DIR', 'components/com_gglms/models/libs/smarty/smarty/');
define('SMARTY_COMPILE_DIR', 'components/com_gglms/models/cache/compile/');
define('SMARTY_CACHE_DIR', 'components/com_gglms/models/cache/');
define('SMARTY_TEMPLATE_DIR', 'components/com_gglms/models/templates/');
define('SMARTY_CONFIG_DIR', 'components/com_gglms/models/');
define('SMARTY_PLUGINS_DIRS', 'components/com_gglms/models/libs/smarty/extras/');

jimport('joomla.application.component.model');
jimport('joomla.user.helper');

class gglmsModelGeneratecoupon extends JModel {
    const DEFAULT_LENGHT = 60;
    const DIRIGENTI_LENGHT = 180;

    const DIRIGENTI_COURSE_ID = '10';


    private $_japp;
    protected $_db;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_japp = & JFactory::getApplication();
        $this->_db = & JFactory::getDbo();
    }

    public function __destruct() {
        
    }

    /**
     *  Crea una nuovo utente nel database di joomla
     *  Attraverso tale utente la sociteta' che ha comprato i coupon per il corso
     *  puo' accedere al downlod degli attestati.
     *  Di default la societa' e' inserita nel gruppo dell'associazione da cui ha acquistato il corso.
     */
    public function create_new_company_user($data) {
        try {
// esiste gia' l'username?
            $user_id = $this->_check_username($data['username']);
            if (empty($user_id)) {
// genero una password casuale
                $password = $this->_generate_pwd(8);
                $salt = JUserHelper::genRandomPassword(32);
                $crypt = JUserHelper::getCryptedPassword($password, $salt) . ':' . $salt;
// creo nuovo user
                $query = sprintf('INSERT INTO #__users (name, username, password, usertype, email, sendEmail, registerDate, activation) VALUES (\'%s\', \'%s\', \'%s\', \'Registered\', \'%s\', 0, NOW(), \'\')', $data['ragione_sociale'], $data['username'], $crypt, $data['email']);
                $this->_db->setQuery($query);
                if (false === $this->_db->query())
                    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
                debug::msg('Nuovo utente ' . $data['username'] . ':' . $password . ' inserito.');
// id del nuovo user
                $user_id = $this->_db->insertid();
                $group_id = $data['id_associazione'];
// inserisco l'utente nella mappa dei gruppi o stesso gruppo dell'utente dell'associazione da cui hanno comprato il corso
                $query = 'INSERT INTO #__user_usergroup_map (user_id, group_id) VALUES (' . $user_id . ', ' . $group_id . ')';
                $this->_db->setQuery($query);
                if (false === $this->_db->query())
                    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
                debug::msg('Nuovo utente associato al gruppo ' . $group_id);

                // creo nuovo gruppo
                if (false === ($company_group_id = $this->_create_company_group($user_id, $data['ragione_sociale'])))
                    throw new Exception('Errore nella creazione del gruppo', E_USER_ERROR);
                // creo nuovo forum
                /** @todo sostituire il 16 della riga sotto con un sistema che prelevi l'ID del gruppo tutor da DB */
                if (false === $this->_create_company_forum($user_id, $company_group_id, $data['ragione_sociale'], $data['id_associazione'], 16))
                    throw new Exception('Errore nella creazione del forum', E_USER_ERROR);

                // inserisco in comprofiler
                $query = 'INSERT INTO #__comprofiler (id, user_id, cb_cognome) VALUES (' . $user_id . ', ' . $user_id . ', \'' . $data['ragione_sociale'] . '\')';
                $this->_db->setQuery($query);
                if (false === $this->_db->query())
                    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
                debug::msg('Nuovo utente aggiornata anagrafica');
            }
            return array('user_id' => $user_id, 'password' => isset($password) ? $password : null);
        } catch (Exception $e) {
            debug::exception($e);
        }
        return false;
    }

    public function insert_coupons($data) {
        try {
// creo utente azienda
            if (false === ($new_user = $this->create_new_company_user($data)))
                throw new RuntimeException('Error: cannot create user.', E_USER_ERROR);

            $group_id = $data['id_associazione'];

// creo i coupon
            $coupons = array();
            $values = array();
            $durata = (false === strpos($data['course_id'], self::DIRIGENTI_COURSE_ID)) ? self::DEFAULT_LENGHT : self::DIRIGENTI_LENGHT;
            for ($i = 0; $i < $data['coupon_number']; $i++) {
                $course_info = $this->_course_prefix($data['course_id']);
                $coupons[$i] = $this->_generate_coupon($course_info['prefisso_coupon'], $data['ragione_sociale']);

                $values[] = sprintf("('%s', '%s', %d, '%s', %d, %d, %d)", $coupons[$i], $data['course_id'], $group_id, $data['transition_id'], $data['attestato'], $new_user['user_id'], $durata);
            }
            // li inserisco nel DB
            $query = 'INSERT INTO #__gg_coupon (coupon, corsi_abilitati, gruppo, id_iscrizione, attestato, id_societa, durata) VALUES ' . join(',', $values);

            $this->_db->setQuery($query);
            if (false === $this->_db->query())
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            debug::msg('Coupon generati');

            $query = 'SELECT dominio, email_riferimento FROM #__usergroups_details WHERE group_id=' . $group_id . ' LIMIT 1';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadRow()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $data['associazione_name'] = $results[0];
            $data['associazione_url'] = 'http://www.' . strtolower($results[0]) . '/';
            $data['email_riferimento'] = $results[1];

            // invio la mail all'azienda con i coupon inseriti
//            require_once('libs/sendmail.class.php');
//            require_once('libs/smarty/EasySmarty.class.php');
//            $mail = new sendmail(MIME_HTML);
//            $mail->from($data['associazione_name'], $data['email_riferimento']);
//            $mail->to($data['email'], RCPT_TO);
//            $mail->to('antonio@ggallery.it', RCPT_BCC);
//            $mail->to($data['email_riferimento'], RCPT_BCC);
//            $mail->subject('Coupon corso ' . $data['associazione_name']);
//            $smarty = new EasySmarty();
//            $data['password'] = $new_user['password'];
//            $smarty->assign('ausind', $data);
//            $smarty->assign('coupons', $coupons);
//            $smarty->assign('coursename', $course_info['corso']);

//            $mail->body($smarty->fetch_template('coupons_mail.tpl', null, true, false, 0));
//            if (!$mail->send())
//                throw new RuntimeException('Error sending mail', E_USER_ERROR);

            
            
            // NUOVO SISTEMA MAILING
            
            require_once('libs/smarty/EasySmarty.class.php');
            $mailer = JFactory::getMailer(); 
            $mailer->setSender($data['associazione_name'], $data['email_riferimento']);
            $recipient = array($results['email_riferimento'], $data['email'], 'antonio@ggallery.it');
            $mailer->addRecipient($recipient);
            $mailer->setSubject('Coupon corso ' . $data['associazione_name']);
            
            $smarty = new EasySmarty();
            $data['password'] = $new_user['password'];
            $smarty->assign('ausind', $data);
            $smarty->assign('coupons', $coupons);
            $smarty->assign('coursename', $course_info['corso']);
            $mailer->setBody($smarty->fetch_template('coupons_mail.tpl', null, true, false, 0));
            $mailer->isHTML(true);
            
            if (!$mailer->Send())
                throw new RuntimeException('Error sending mail', E_USER_ERROR);
            
            // FINE NUOVO SISTEMA MAILING
            
            
            // e buona notte al secchio
            debug::msg('success');
            return true;
        } catch (Exception $e) {
            debug::exception($e);
            return false;
        }
    }

      public function _course_prefix($id) {
        try {
            // li inserisco nel DB
            $query = '
            SELECT corso, 
            c.prefisso_coupon
            FROM ihyb8_gg_corsi as c
            WHERE
            id= '. $id;
            
            debug::msg($query);
            $this->_db->setQuery($query);
            if (false === ($result =  $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
                
                return $result;

            }
            catch (Exception $e)
            {
                debug::exception($e);
            }

        }

    private function _generate_coupon($prefisso, $usr_ragionesociale) {
        return str_replace(' ', '_', $prefisso.substr($usr_ragionesociale, 0, 3)) . str_replace('0', 'k', md5(uniqid('', true))); // no zeros
    }

    private function _check_username($username) {
        $query = 'SELECT id FROM #__users WHERE username=\'' . $username . '\' LIMIT 1';
        $this->_db->setQuery($query);
        if (false === ($results = $this->_db->loadRow()))
            throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
        debug::vardump($results, 'società');
        return isset($results[0]) ? $results[0] : null;
    }

    private function _generate_pwd($l = 8) {
        return chr(65 + rand(0, 1) * 32 + rand(0, 25)) . ($l ? $this->_generate_pwd(--$l) : '');
    }

    public function check_Coupon($coupon) {
        try {
            $query = '
                SELECT
                    *
                FROM
                    #__gg_coupon as c
                WHERE
                    c.coupon = "' . $coupon . '"
                        AND
                    c.id_utente IS NULL
                ';

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $this->_coupon = empty($results) ? array() : $results;
        } catch (Exception $e) {
            $this->_coupon = array();
        }
        return $this->_coupon;
    }

    public function verifica_Abilitato($id_iscrizione) {
        $this->_ausind_confindustria_option = array(
            'driver' => 'mysql',
            'host' => '217.133.179.135',
            'user' => 'ausind',
            'password' => 'aus2012db',
            'database' => 'chrausind'
        );
        $this->_db2 = & JDatabase::getInstance($this->_ausind_confindustria_option);


//TODO VERIFICARE LA CORRETTEZZA DELLA QUERY!!!!!! ******************************************************
        try {
            $query = '
                SELECT
                    i.cis_tipoPagamento as result
                FROM
                    crsiscrizioni AS i
                WHERE
                    i.cis_id = "' . $id_iscrizione . '"
                ';

            $this->_db2->setQuery($query);
            if (false === ($results = $this->_db2->loadAssoc()))
                throw new RuntimeException($this->_db2->getErrorMsg(), E_USER_ERROR);
            $this->_abilitato = empty($results) ? array() : $results;
        } catch (Exception $e) {
            $this->_abilitato = array();
        }
        $this->abilita_Coupon($id_iscrizione);
        return $this->_abilitato;
    }

    public function abilita_Coupon($id_iscrizione) {
        try {
            $query = '
                UPDATE
                    #__gg_coupon as c
                SET
                    c.abilitato = 1,
                    c.id_utente = ' . $this->_userid . '
                WHERE
                    c.id_iscrizione= "' . $id_iscrizione . '"
                ';

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $this->_abilitato = empty($results) ? array() : $results;
        } catch (Exception $e) {
            $this->_abilitato = array();
        }

        return $this->_abilitato;
    }

    /**
     * Crea un nuovo gruppo con il nome della società ($company_name) gerarchicamente sotto il gruppo "Società Acquirenti" (21) e associa
     * l'id della società ($company_id) al gruppo appena creato.
     * L'inserimento del nuovo gruppo viene fatto chiamando la Store Procedure "usergroups_insert":
     * 
     * DROP PROCEDURE IF EXISTS usergroups_insert;
     * DELIMITER $$
     * CREATE PROCEDURE usergroups_insert (IN t VARCHAR(100), IN p INT(10) UNSIGNED, OUT out_id INT(10) UNSIGNED)
     * BEGIN
     *      SELECT @myLeft := lft FROM ihyb8_usergroups WHERE id=p;
     *      UPDATE ihyb8_usergroups SET rgt = rgt + 2 WHERE rgt > @myLeft;
     *      UPDATE ihyb8_usergroups SET lft = lft + 2 WHERE lft > @myLeft;
     *      INSERT INTO ihyb8_usergroups (title, parent_id, lft, rgt) VALUES (t, p, @myLeft + 1, @myLeft + 2);
     *      SELECT LAST_INSERT_ID() INTO out_id FROM ihyb8_usergroups LIMIT 1; 
     * END$$
     * DELIMITER ;
     * 
     * @todo usare le API di Joomla per creare il gruppo.
     * 
     * @param int $company_id 
     * @param string $company_name
     * @return int Ritorn l'ID del gruppo appena creato o FALSE in caso di errore.
     */
    private function _create_company_group($company_id, $company_name) {
        try {
            $company_name = filter_var($company_name, FILTER_SANITIZE_STRING);
            if (empty($company_name))
                throw new BadMethodCallException('Company name not set or invalid', E_USER_ERROR);
            $company_id = filter_var($company_id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
            if (empty($company_id))
                throw new BadMethodCallException('Company ID not set or invalid', E_USER_ERROR);

            // creazione nuovo gruppo
            $query = 'SELECT @myLeft := lft FROM #__usergroups WHERE id=21';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $query = 'UPDATE #__usergroups SET rgt = rgt + 2 WHERE rgt > @myLeft';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $query = 'UPDATE #__usergroups SET lft = lft + 2 WHERE lft > @myLeft';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $query = 'INSERT INTO #__usergroups (title, parent_id, lft, rgt) VALUES (\'' . $company_name . '\', 21, @myLeft + 1, @myLeft + 2)';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

            // selezione l'ID del gruppo appena inserito
            $query = 'SELECT LAST_INSERT_ID() AS id';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

            $company_group_id = filter_var($results['id'], FILTER_VALIDATE_INT);
            if (empty($company_group_id))
                throw RuntimeException('Cannot get group ID from database', E_USER_ERROR);

            // associo la sociatà al suo gruppo
            $query = 'INSERT INTO #__user_usergroup_map (user_id, group_id) VALUES (' . $company_id . ', ' . $company_group_id . ')';

            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            debug::msg('Gruppo società ' . $company_group_id . ' creato');
            return $company_group_id;
        } catch (Exception $e) {
            debug::exception($e);
            return false;
        }
    }

    /**
     * Crea un livello di accesso utilizzato per accedere al Froum della sociatà acquirente. Possono accedere al forum:
     * -  tutti i dipendenti di quella società
     * - l'utente della società
     * - i tutor dell'associazione venditrice
     * 
     * @param string $company_name Nome della società acquirente usato per creare il nome del livello di accesso
     * @param type $company_group_id ID del gruppo della società acquirente
     * @param type $seller_group_id ID del gruppo della società venditrice affiliata 
     * @return int Ritorna l'ID del livello di accesso o FALSE in caso di errore.
     */
    private function _create_company_access_level($company_name, $company_group_id, $seller_group_id, &$tutor_group_id) {
        try {
            $company_name = addslashes(filter_var($company_name, FILTER_SANITIZE_STRING));
            if (empty($company_name))
                throw new BadMethodCallException('Company name not set or invalid', E_USER_ERROR);
            $company_group_id = filter_var($company_group_id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
            if (empty($company_group_id))
                throw new BadMethodCallException('Company Group ID not set or invalid', E_USER_ERROR);
            $seller_group_id = filter_var($seller_group_id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
            if (empty($seller_group_id))
                throw new BadMethodCallException('Seller Group ID not set or invalid', E_USER_ERROR);

            // recupero l'ID del gruppo dei tutor dell'associazione venditrice: quel gruppo che è figlio del gruppo dell'associazione stessa.
            $query = 'SELECT id FROM #__usergroups WHERE parent_id=' . $seller_group_id . ' LIMIT 1';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $tutor_group_id = filter_var($results['id'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
            if (empty($tutor_group_id))
                throw RuntimeException('Cannot get Tutor Group ID from database', E_USER_ERROR);

            // inserisco nuovo livello di accesso
            $query = 'INSERT INTO #__viewlevels (title, rules) VALUES (\'Forum ' . $company_name . '\', \'[' . $company_group_id . ',' . $tutor_group_id . ']\')';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

            // selezione l'ID del livello appena inserito
            $query = 'SELECT LAST_INSERT_ID() AS id';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $access_level = filter_var($results['id'], FILTER_VALIDATE_INT);
            if (empty($access_level))
                throw RuntimeException('Cannot get access level ID from database', E_USER_ERROR);
            return $access_level;
        } catch (Exception $e) {
            debug::exception($e);
            return false;
        }
    }

    /**
     * Crea una nuova categoria del forum Kunena per la classe virtuale della società. L'accesso è garantito ai soli utenti appartenenti al grupppo della società ($company_group_id).
     * Il nome della nuova categoria del form sarà il nome della società ($company_name) preceduto da "Classe ".
     * 
     * @todo creare anche post di benvenuto.
     * 
     * @param int $company_id
     * @param int $company_group_id
     * @param string $company_name
     * @param int $seller_group_id ID dell'associazione venditrice
     * @return bool
     */
    private function _create_company_forum($company_id, $company_group_id, $company_name, $seller_group_id) {
        try {
            $company_id = filter_var($company_id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
            if (empty($company_id))
                throw new BadMethodCallException('Company ID not set or invalid', E_USER_ERROR);
            $company_name = filter_var($company_name, FILTER_SANITIZE_STRING);
            if (empty($company_name))
                throw new BadMethodCallException('Company name not set or invalid', E_USER_ERROR);
            $company_group_id = filter_var($company_group_id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
            if (empty($company_group_id))
                throw new BadMethodCallException('Company Group ID not set or invalid', E_USER_ERROR);

            $tutor_group_id = null;
            $access_level = $this->_create_company_access_level($company_name, $company_group_id, $seller_group_id, $tutor_group_id);
            $name = 'Classe ' . $company_name;
            $alias = str_replace(' ', '-', filter_var(strtolower($name), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            $description = 'Forum di discussione della società ' . $company_name;
            $parmas = '{"access_post":["' . $company_group_id . '","' . $tutor_group_id . '"],"access_reply":["' . $company_group_id . '","' . $tutor_group_id . '"]}';
            $query = 'INSERT INTO #__kunena_categories (parent_id, name, alias, accesstype, access, ordering, published, description, headerdesc, params)
                VALUES (1, \'' . $name . '\', \'' . $alias . '\', \'joomla.level\', ' . $access_level . ', 3, 1, \'' . $description . '\', \'' . $description . '\', \'' . $parmas . '\')';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

            // ID della categoria del forum appena creata
            $query = 'SELECT LAST_INSERT_ID() AS id';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->loadAssoc()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $company_forum_id = filter_var($results['id'], FILTER_VALIDATE_INT);
            if (empty($company_forum_id))
                throw RuntimeException('Cannot get forum ID from database', E_USER_ERROR);

            // l'utente della società è moderatore del forum
            $query = 'INSERT INTO #__kunena_user_categories (user_id, category_id, role) VALUES (' . $company_id . ', ' . $company_forum_id . ', 1)';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            $query = 'INSERT INTO #__kunena_users (userid, moderator, rank) VALUES (' . $company_id . ', 1, 8) ON DUPLICATE KEY UPDATE moderator=1, rank=8';
            $this->_db->setQuery($query);
            if (false === ($results = $this->_db->query()))
                throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);
            return true;
        } catch (Exception $e) {
            debug::exception($e);
            return false;
        }
    }

}

// ~@:-]
