<?php

class console_log_cloaking {
    function __construct() {
        self::init();
    }

    /** 
     * Outupts the proper console logging script in <head> if logging is disabled
     */
    private function init(): void {
        require_once(ABSPATH . "wp-includes/pluggable.php");
        if (!self::log_enabled()) {
            add_action('wp_head', function() { ?>
<script>if(void 0===console)var console={};<?php echo self::js_ConsoleExclusions(); ?>=function(){};</script>
            <?php }, true);
        }

    }

    /**
     * Returns a JS string (partial) equating all the log type exclusions
     */
    protected function js_ConsoleExclusions(): string {
        $exclusions = (array) get_option('lo_logs');
        $array = [];
        foreach($exclusions as $ex) {
            $array[] = 'console.'.$ex;
        }
        return implode('=',$array);    
    }

    /**
     * Returns an array of roles associated with the current user
     */
    private function current_user_roles(): array {
        if( is_user_logged_in() ) {
            $user = wp_get_current_user();
            $roles = ( array ) $user->roles;
            return $roles; 
        } else {
            return array();
        }
    }    

    /**
     * Verifies that console logging is enabled
     */
    private function log_enabled(): bool {
        if (get_option('lo_enabled')[0] === 'enable') {
            return true;
        }
        if (!is_user_logged_in()) {
            return false;
        }
        $is_admin = in_array('administrator', self::current_user_roles());
        $is_enabled = !empty(array_intersect(array_map('strtolower', (array) self::current_user_roles()), array_map('strtolower', (array) get_option('lo_roles'))));
        return ( $is_admin || $is_enabled ) ? true : false;
    }
}
