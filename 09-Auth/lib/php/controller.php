<?php
// controller.php 20151015 (C) 2015 Mark Constable <markc@renta.net> (AGPL-3.0)

declare(strict_types = 1);

session_start();
//$_SESSION = [];
error_log('GET='.var_export($_GET, true));
error_log('POST='.var_export($_POST, true));
error_log('SESSION='.var_export($_SESSION, true));

class Controller
{
    private $g = null;
    private $t = null;

    public function __construct($g)
    {
        $this->g = $g;
        util::cfg($g);
        $g->in = util::esc($g->in);
        $g->in['l'] = util::ses('l', $g->in['l']);
        $g->in['t'] = util::ses('t', $g->in['t']);
        $theme = 'themes_' . $g->in['t'] . '_view';
        $t = $this->t = class_exists($theme) ? new $theme($g) : new View($g);
        $m = new Model($t, $g); // throwaway returned object
        foreach ($g->out as $k => $v)
            $g->out[$k] = method_exists($t, $k) ? $t->$k() : $v;
    }

    public function __destruct()
    {
        error_log($_SERVER['REMOTE_ADDR'].' '.round((microtime(true)-$_SERVER['REQUEST_TIME_FLOAT']), 4));
    }

    public function __toString() : string
    {
        if (method_exists($this->t, 'html')) {
            if ($this->g->in['a'] === 'json') {
                header('Content-Type: application/json');
                return json_encode($this->g->out, JSON_PRETTY_PRINT);
            }
            return $this->t->html();
        } else return "Error: no theme available";
    }
}
