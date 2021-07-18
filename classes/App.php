<?

    class App
    {

        /** @var User $User */
        private $User;

        public $mysql;
        public $redis;



        private $content;

        private $page_name = "auth";

        public $query_string;

        public function __construct($User = false) {
            $this->prepare($User);
            $this->build();
            $this->output();
        }

        private function prepare($User) {

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

            $this->User = $User ?: new User();

        }

        private function build() {

            $App = $this;
            $User = $this->User;

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