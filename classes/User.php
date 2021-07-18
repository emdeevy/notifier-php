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
        }



        public function login($email = false, $password = false) {
            // login script
        }

        public function logout() {
            // logout script
        }



        public function isLoggedIn() {
            return ($this->status === UserType::MEMBER);
        }

        public function isMember() {
            return ($this->status === UserType::MEMBER);
        }

        public function isVisitor() {
            return ($this->status === UserType::VISITOR);
        }

    }