<?php
namespace SpringSys;

class Template{
    protected string $tpl_dir;
    protected string $error_msg = '';
    protected string $success_msg = '';

    public Config $cfg;
    public function __construct(Config $cfg = new \SpringSys\Config()){
        $tpl_dir = $cfg->get('template_dir');
        if (!file_exists($tpl_dir)){
            throw new \Exception("Template dir does not exist: $tpl_dir");
        }
        if (!is_dir($tpl_dir)){
            throw new \Exception("Template is not a directory: $tpl_dir");
        }
        $this->tpl_dir = realpath($tpl_dir);
        $this->cfg = $cfg;
    }

    /**
     * Load a template
     * @param string $template
     * @param array $vars
     * @return void
     * @throws \Exception
     */
    public function load(string $template, array $vars = []): void{
        $template = str_replace('..','',$template);
        $tpl_file = $this->tpl_dir.'/'.$template.'.tpl.php';
        if (!file_exists($tpl_file)){
            throw new \Exception("Template does not exist: $template");
        }
        //use this precaution to guard against people changing tpl_file and including random files
        if (isset($vars['tpl_file'])){
            unset($vars['tpl_file']);
        }
        //NEVER allow overriding of $this
        if (isset($vars['this'])){
            throw new \Exception("Invalid variable name 'this'");
        }
        extract($vars);
        include($tpl_file);
    }

    /**
     * Load the header
     * @param $title
     * @return void
     * @throws \Exception
     */
    public function header($title, $show_h1 = true): void{
        $this->load('header', ['title'=>$title, 'show_h1'=>$show_h1]);
    }

    /**
     * Load the footer
     * @return void
     * @throws \Exception
     */
    public function footer(): void{
        $this->load('footer');
    }

    public function setError(string $msg){
        $this->error_msg = $msg;
    }

    public function setSuccess(string $msg){
        $this->success_msg = $msg;
    }
}