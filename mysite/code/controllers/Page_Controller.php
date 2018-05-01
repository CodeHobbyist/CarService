<?php

class Page_Controller extends ContentController
{
    /**
     * An array of actions that can be accessed via a request. Each array element should be an action name, and the
     * permissions or conditions required to allow the user to access it.
     *
     * <code>
     * array (
     *     'action', // anyone can access this action
     *     'action' => true, // same as above
     *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
     *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
     * );
     * </code>
     *
     * @var array
     */
    private static $allowed_actions = array(
    );

    public function init()
    {
        parent::init();
        Requirements::css($this->ThemeDir().'/css/bootstrap.min.css');
        Requirements::css($this->ThemeDir().'/css/style.css');
        Requirements::css($this->ThemeDir().'/css/responsive.css');
        Requirements::javascript($this->ThemeDir().'/javascript/libs/min/vendor.js');
        Requirements::javascript($this->ThemeDir().'/javascript/particles.js');
        Requirements::javascript($this->ThemeDir().'/javascript/modernizer.js');
        Requirements::javascript($this->ThemeDir().'/javascript/libs/min/custom.js');
    }
    
    public function Testimonials($count) {
        return Testimonial::get()
                    ->limit($count);
    }

    public function Galleries()
    {
        return Gallery::get();
    }
}
