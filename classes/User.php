<?

    class User
    {

        public $id;

        public $first_name = "First Name...";
        public $last_name = "Last Name...";
        public $email = "Email...";

        public $profile_picture = false;

        public $status = UserType::VISITOR;

        public function __construct($id = -1) {
            $this->id = $id;

            $this->status = ($this->sessionLogged()) ? UserType::MEMBER : UserType::VISITOR;
        }



        public function login($email, $password, &$mysql, &$redis) {
            // login script
        }

        public function logout() {
            // logout script
        }



        public function isLoggedIn() {
            return ($this->status === UserType::MEMBER);
        }

        public function logged() {
            return $this->isLoggedIn();
        }

        public function remembered() {

        }

        public function sessionLogged() {

            return (session_status() !== PHP_SESSION_ACTIVE ? false : (!isset($_SESSION[Session::LOGGED_KEY]) ? false : $_SESSION[Session::LOGGED_KEY]));

        }

        public function isMember() {
            return ($this->status === UserType::MEMBER);
        }

        public function isVisitor() {
            return ($this->status === UserType::VISITOR);
        }

    }