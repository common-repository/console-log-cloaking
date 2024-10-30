<?php
class console_log_cloaking_admin
{

    const LOGTYPES = ['log', 'error', 'info', 'debug', 'warn', 'trace', 'dir', 'dirxml', 'group', 'groupEnd', 'time', 'timeEnd', 'assert', 'profile'];

    /**
     * Initializes actions and filters
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'create_plugin_settings_page']);
        add_action('admin_init', [$this, 'setup_sections']);
        add_action('admin_init', [$this, 'setup_fields']);
        add_action('admin_init', [$this, 'lo_scripts']);
        new console_log_cloaking(); // initialize
    }

    /**
     * Sets up the admin page
     */
    public function create_plugin_settings_page()
    {
        $page_title = 'Console Log Cloaking Settings';
        $menu_title = 'Console Log Cloaking';
        $capability = 'manage_options';
        $slug = 'lo_settings';
        $callback = [$this, 'plugin_settings_page_content'];
        $icon = 'dashicons-editor-strikethrough';
        $position = 300;
        add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
    }

    /**
     * Basic page setup
     */
    public function plugin_settings_page_content()
    {?>
    <div class="wrap">
    	<h2>Console Log Cloaking Settings</h2>
    <?php
    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
        self::admin_notice();
    }?>
    	<form method="POST" action="options.php">
        <?php
        settings_fields('lo_fields');
        do_settings_sections('lo_fields');
        submit_button();
        ?>
    	</form>
    </div>
    <?php
}

    /**
     * Notification setup
     */
    public function admin_notice()
    {?>
        <div class="notice notice-success is-dismissible">
            <p>Your Console Log Cloaking settings have been updated!</p>
        </div>
    <?php
}

    /**
     * Add page sections
     */
    public function setup_sections()
    {
        add_settings_section('main_section', 'General Settings', [$this, 'section_callback'], 'lo_fields');
    }

    /**
     * Page section router
     * @param array $arguments
     */
    public function section_callback($arguments)
    {
        switch ($arguments['id']) {
            case 'main_section':
                echo '<ul style="font-size: 1.2em;"><li>Configure the Console Log Cloaking options below.</li><li>Visit <a href="https://plugins.codecide.net/product/lo" target="_blank">the official plugin page</a> for detailed information. </li></ul>';
                break;
        }
    }

    /**
     * Setup the fields
     */
    public function setup_fields()
    {
        $fields = [
            [
                'uid' => 'lo_enabled',
                'label' => 'Console Logging',
                'section' => 'main_section',
                'type' => 'radio',
                'options' => [
                    'enable' => 'On',
                    'disable' => 'Off',
                ],
                'supplemental' => '[required] Enable or disable console logging for users and visitors.',
                'default' => ['disable'],
            ],
            [
                'uid' => 'separator',
                'type' => 'html',
                'section' => 'main_section',
                'label' => null,
                'value' => '<h3>Options</h3>',
                'default' => null
            ],
            [
                'uid' => 'lo_roles',
                'label' => 'Enable for Roles',
                'section' => 'main_section',
                'type' => 'checkbox',
                'options' => self::all_roles(['Administrator']),
                'supplemental' => '[Optional] Enable console logging for the checked roles. Note that logs are always visible to site administrators.',
                'default' => [''],
            ],
            [
                'uid' => 'lo_logs',
                'label' => 'Disable for Types',
                'section' => 'main_section',
                'type' => 'checkbox',
                'options' => self::all_logs(),
                'supplemental' => 'Disable console logging for the above log types. Types that are un-checked will be visible to all users.',
                'default' => self::LOGTYPES,
            ],
        ];
        foreach ($fields as $field) {
            add_settings_field($field['uid'], $field['label'], [$this, 'field_callback'], 'lo_fields', $field['section'], $field);
            register_setting('lo_fields', $field['uid']);
        }
    }

    /**
     * Field templates
     * @param array $arguments An array of properties to build fields from
     */
    public function field_callback(array $arguments)
    {
        $value = get_option($arguments['uid']);
        if (!$value) {
            $value = $arguments['default'];
        }
        switch ($arguments['type']) {
            case 'text':
            case 'password':
            case 'number':
                printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], @$arguments['placeholder'], $value);
                break;
            case 'textarea':
                printf('<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value);
                break;
            case 'select':
            case 'multiselect':
                if (!empty($arguments['options']) && is_array($arguments['options'])) {
                    $attributes = '';
                    $options_markup = '';
                    foreach ($arguments['options'] as $key => $label) {
                        $options_markup .= sprintf('<option value="%s" %s>%s</option>', $key, selected($value[array_search($key, $value, true)], $key, false), $label);
                    }
                    if ($arguments['type'] === 'multiselect') {
                        $attributes = ' multiple="multiple" ';
                    }
                    printf('<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup);
                }
                break;
            case 'radio':
            case 'checkbox':
                if (!empty($arguments['options']) && is_array($arguments['options'])) {
                    $options_markup = '';
                    $iterator = 0;
                    foreach ($arguments['options'] as $key => $label) {
                        $iterator++;
                        $options_markup .= sprintf(
                            '<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>',
                            $arguments['uid'],
                            $arguments['type'],
                            $key,
                            checked($value[array_search($key, $value, true)], $key, false),
                            $label,
                            $iterator
                        );
                    }
                    printf('<fieldset>%s</fieldset>', $options_markup);
                }
                break;
            case 'html':
                printf('<div id="%1$s" class="html-container">%2$s</div>', $arguments['uid'], $arguments['value']);
                break;
        }
        if ($helper = @$arguments['helper']) {
            printf('<span class="helper"> %s</span>', $helper);
        }
        if ($supplemental = @$arguments['supplemental']) {
            printf('<p class="description">%s</p>', $supplemental);
        }
    }

    /**
     * Returns an array of all user role names, sorted alpha
     */
    public function all_roles(?array $excluded = null): array
    {
        require_once ABSPATH . "wp-includes/pluggable.php";
        $roles = wp_roles()->get_names();
        $result = [];
        foreach ($roles as $role) {
            if (!in_array($role, $excluded)) {
                $result[] = translate_user_role($role);
            }
        }
        sort($result);
        return (array) array_combine($result, $result);
    }

    /**
     * Returns an array of all log type names, sorted alpha
     */
    public function all_logs(): array
    {
        $logs = self::LOGTYPES;
        sort($logs);
        return (array) array_combine($logs, $logs);
    }

    /**
     * Script injector
     */
    public function lo_scripts()
    {
        // wp_register_script('console_log_cloaking-admin', plugins_url('js/console_log_cloaking.admin.js', __FILE__), filemtime(plugin_dir_path(__FILE__) . 'js/console_log_cloaking.admin.js'), true);
        wp_register_script('console_log_cloaking-admin',  plugins_url('js/console_log_cloaking.admin.js', dirname(dirname(__FILE__ ))),"1.0", true);
        wp_enqueue_style('console_log_cloaking-admin', plugins_url('css/console_log_cloaking.admin.css', dirname(dirname(__FILE__))));
        wp_enqueue_script('console_log_cloaking-admin');
    }

}
