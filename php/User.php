<?php
    class User{
        public $user_id;
        public $fname;
        public $mname;
        public $lname;
        public $designation;
        public $picture;
        public $username;
        public $password;

        public function __construct($fname, $mname, $lname, $designation, $username, $password)
        {
            $this->fname = $fname;
            $this->mname = $mname;
            $this->lname = $lname;
            $this->designation = $designation;
            $this->username = $username;
            $this->password = $password;
        }
    }
