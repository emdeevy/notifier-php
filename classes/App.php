<?

    class App
    {

        const COMMAND_MODE = 1;
        const OUTPUT_MODE = 2;

        public $mode;

        /** @var User $User */
        private $User;

        public $mysql;
        public $redis;

        private $content;

        private $page_name = "auth";

        public $query_string;

        public function __construct($User = false) {
            $this->prepare($User);

            if($this->mode === self::OUTPUT_MODE) {
                $this->build();
                $this->output();
            }

            if($this->mode === self::COMMAND_MODE) {
                $acceptedCommands = ['login', 'register'];

                $command = $this->query_string['command'];

                if(!ctype_alpha($command) || !in_array($command, $acceptedCommands)) {
                    header("Location: /?errno=" . ErrorCode::INVALID_COMMAND);
                    exit();
                }

                switch($command) {
                    case 'login':
                        $this->User->login($_POST['email'], $_POST['password'], $this->mysql, $this->redis);
                        break;
                    case 'register':
                        var_dump($_POST);
                        break;
                }
            }
        }

        private function prepare($User) {

            session_start();

            // <editor-fold desc="Mysqli connection saved into App::mysql">
            $mysql = new mysqli(Database::MYSQL_SERVER_NAME, Database::MYSQL_USERNAME, Database::MYSQL_PASSWORD, Database::MYSQL_DATABASE_NAME);

            if($mysql->connect_error) {
                header("Location: /?page=error&error=mysqli");
            }
            // </editor-fold>
            // <editor-fold desc="Redis connection saved into App::redis"
            $redis = new Redis();
            $redis->connect(Database::REDIS_HOST);
            // </editor-fold>

            $this->mode = (isset($_GET['command']) && ctype_alpha($_GET['command'])) ? self::COMMAND_MODE : self::OUTPUT_MODE;
            $this->User = $User ?: new User();

            $this->query_string = $_GET;

        }

        private function build() {

            $App = $this;
            $User = $this->User;

            $default_pages = [
                UserType::VISITOR => 'auth',
                UserType::MEMBER => 'app'
            ];

            $allowed_pages = [
                UserType::VISITOR => ['auth', 'error'],
                UserType::MEMBER => ['app']
            ];

            $requested_page = isset($this->query_string['page']) ?: $default_pages[$User->status];

            if(strlen($requested_page) > 0 && ctype_alpha($requested_page) && in_array($requested_page, $allowed_pages[$User->status])) {
                $this->page_name = $requested_page;
            }
            else {
                $this->page_name = $default_pages[$User->status];
            }

            ob_start();

            require_once(__DIR__ . "/../html/header.php");
            require_once(__DIR__ . "/../html/content.php");
            require_once(__DIR__ . "/../html/footer.php");

            $this->content = ob_get_contents();
            ob_end_clean();

        }

        private function output() {
            echo($this->content);
        }

    }