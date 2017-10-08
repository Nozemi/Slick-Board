<?php
    namespace ForumLib\Forums;

    use ForumLib\Database\DBUtil;

    use ForumLib\Integration\Nozum\NozumVarious;
    use ForumLib\Integration\vB3\vB3Various;
    use ForumLib\Utilities\Config;
	
    class Various {
        private $S;

        private $lastError = array();
        private $lastMessage = array();

        public function __construct(DBUtil $_SQL) {
            if($_SQL instanceof DBUtil) {
                $this->S = $_SQL;
                $this->config = new Config;

                switch($this->config->getConfigValue('integration')) {
                    case 'vB3':
                        $this->integration = new vB3Various($this->S);
                        break;
                    case 'Nozum':
                    default:
                        $this->integration = new NozumVarious($this->S);
                        break;
                }
                $this->lastMessage[] = 'Successfully created an object instance.';
            } else {
                $this->lastError[] = 'The parameter provided wasn\'t an instance of PSQL.';
                $this->__destruct();
            }
        }

        public function __destruct() {
            $this->S = null;
        }

        public function getLatestPosts() {
            return $this->integration->getLatestPosts($this);
        }
    }