<?php
// Central functions for QUIZZEO project (inserts compatibility layer for file-based storage)
if(!defined('QUIZZEO_INIT')){
    define('QUIZZEO_INIT', true);
    session_start();
    define('QUIZZEO_BASE', __DIR__ . '/../');
    define('QUIZZEO_STORAGE', QUIZZEO_BASE . 'data/storage.json');

    if(!file_exists(QUIZZEO_BASE.'data')){
        @mkdir(QUIZZEO_BASE.'data', 0755, true);
    }

    if(!file_exists(QUIZZEO_STORAGE)){
        $admin_hash = password_hash('adminpass', PASSWORD_DEFAULT);
        $init = ['users'=>[['id'=>1,'role'=>'admin','email'=>'admin@quizzeo.local','name'=>'Admin','password'=>$admin_hash,'active'=>true]], 'quizzes'=>[]];
        file_put_contents(QUIZZEO_STORAGE, json_encode($init, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }

    function q_load_data(){
        $s = @file_get_contents(QUIZZEO_STORAGE);
        return $s ? json_decode($s, true) : ['users'=>[], 'quizzes'=>[]];
    }
    function q_save_data($data){
        file_put_contents(QUIZZEO_STORAGE, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }
    function q_find_user_by_email($email){
        $data = q_load_data();
        foreach($data['users'] as $u) if(strtolower($u['email'])==strtolower($email)) return $u;
        return null;
    }
    function q_current_user(){
        if(!isset($_SESSION['user_id'])) return null;
        $data = q_load_data();
        foreach($data['users'] as $u) if($u['id']==$_SESSION['user_id']) return $u;
        return null;
    }
    function q_require_login(){
        $user = q_current_user();
        if(!$user){
            header('Location: ' . (dirname($_SERVER['SCRIPT_NAME'])=="/" ? '/index.php' : dirname($_SERVER['SCRIPT_NAME']).'/index.php'));
            exit;
        }
        return $user;
    }
    function q_next_id($arr){
        $max = 0;
        foreach($arr as $a) if(isset($a['id']) && $a['id']>$max) $max = $a['id'];
        return $max+1;
    }
}
?>