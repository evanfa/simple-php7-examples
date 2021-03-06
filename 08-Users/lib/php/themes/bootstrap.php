<?php
// bootstrap.php 20151015 (C) 2015 Mark Constable <markc@renta.net> (AGPL-3.0)

declare(strict_types = 1);

error_log(__FILE__);

class Themes_Bootstrap extends View
{
    public function css() : string
    {
        return '
    <link href="//fonts.googleapis.com/css?family=Roboto:400,100,300,100italic" rel="stylesheet" type="text/css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <style>
* { transition: 0.2s linear; }
body {
  background: url("/simple-php7-examples/lib/img/20130317_Albert_Park_800x144.jpg") fixed top no-repeat;
  background-size: 100%;
  font-family: "Roboto", sans-serif; font-weight: 300;
}
.demo-content {
  background-color: #FFF;
  margin-bottom: 4em;
  padding-bottom: 1em;
}
footer {
  background-color: #424242;
  bottom: 0px;
  color: #9E9E9E;
  padding: 2em 1em 3em;
  position: fixed;
  width: 100%;
  z-index: -99;
}
td {
    white-space: nowrap;
}
@media(min-width:767px){
  .alert { margin-top: 1em; }
  .demo-content {
    border-radius: 0.2em;
    box-shadow: 0px 4px 5px 0px rgba(0, 0, 0, 0.14), 0px 1px 10px 0px rgba(0, 0, 0, 0.12), 0px 2px 4px -1px rgba(0, 0, 0, 0.2);
  }
}
</style>';
    }

    public function msg() : string
    {
        list($l, $m) = $this->g->in['m']
            ? explode(':', $this->g->in['m']) : util::msg();
        return $m ? '
      <div class="alert alert-'.$l.'">'.$m.'
      </div>' : '';
    }

    public function head() : string
    {
        return '
    <header class="navbar navbar-inverse navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">'.$this->g->out['head'].'</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">'.$this->g->out['nav1'].'
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Themes <span class="caret"></span></a>
              <ul class="dropdown-menu">'.$this->g->out['nav2'].'
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </header>';
    }

    public function nav1(array $a = []) : string
    {
        $a = isset($a[0]) ? $a : $this->g->nav1;
        $p = '?p='.$this->g->in['p'];
        $t = '?t='.$this->g->in['t'];
        return join('', array_map(function ($n) use ($p, $t) {
            $c = $p === $n[1] || $t === $n[1] ? ' class="active"' : '';
            return '
            <li'.$c.'><a href="'.$n[1].'">'.$n[0].'</a></li>';
        }, $a));
    }

    public function nav2() : string
    {
        return $this->nav1($this->g->nav2);
    }

    public function main() : string
    {
        return '
    <main class="container">
      <div class="row">
        <div class="col-md-1"></div>
        <div class="demo-content col-md-10">'.$this->g->out['msg'].$this->g->out['main'].'
        </div>
        <div class="col-md-1"></div>
      </div>
    </main>';
    }

    public function end() : string
    {
        return '
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>';
    }

    public function veto_a($href, $label, $class, $extra)
    {
        $class = $class ? ' btn-'.$class : '';
        return ['class' => 'btn btn-primary'.$class];
    }

    public function veto_button($label, $type, $class, $name, $value, $extra)
    {
        $class = $class ? ' btn-'.$class : '';
        return ['class' => 'btn btn-primary'.$class];
    }

    public function veto_email_contact_form()
    {
        return '
      <form class="form-horizontal" role="form" method="post" onsubmit="return mailform(this);">
        <div class="form-group">
          <label for="subject" class="col-sm-2 col-md-3 col-lg-4 control-label">Subject</label>
          <div class="col-sm-9 col-md-7 col-lg-5">
            <input type="text" class="form-control" id="subject" placeholder="Your Subject" required>
          </div>
        </div>
        <div class="form-group">
          <label for="message" class="col-sm-2 col-md-3 col-lg-4 control-label">Message</label>
          <div class="col-sm-9 col-md-7 col-lg-5">
            <textarea class="form-control" id="message" rows="9" placeholder="Your Message" required></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-2 col-sm-offset-2 col-md-offset-3 col-lg-offset-4">
            <input class="btn btn-primary" id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
          </div>
        </div>
      </form>';
    }

    // Notes

    public function veto_notes_item($ary) : string
    {
        extract($ary);
        return '
      <table class="table">
        <tr>
          <td><a href="?p=notes&a=read&i=' . $id . '">' . $title . '</a></td>
          <td style="text-align:right">
            <small>
              by <b>' . $author . '</b> - <i>' . util::now($updated) . '</i> -
              <a href="?p=notes&a=update&i=' . $id . '" title="Update">E</a>
              <a href="?p=notes&a=delete&i=' . $id . '" title="Delete" onClick="javascript: return confirm(\'Are you sure you want to remove '.$id.'?\')">X</a>
            </small>
          </td>
        </tr>
        <tr>
          <td colspan="2">' . nl2br($content) . '</td>
        </tr>
      </table>';
    }

    public function veto_notes_form($ary) : string
    {
        extract($ary);
        return '
      <form class="form-horizontal" role="form" method="post"">
        <div class="form-group">
          <label for="title" class="col-sm-2 col-md-3 col-lg-4 control-label">Title</label>
          <div class="col-sm-9 col-md-7 col-lg-5">
            <input type="text" class="form-control" id="title" name="title" placeholder="Your Title" value="' . $title . '" required>
          </div>
        </div>
        <div class="form-group">
          <label for="author" class="col-sm-2 col-md-3 col-lg-4 control-label">Author</label>
          <div class="col-sm-9 col-md-7 col-lg-5">
            <input type="text" class="form-control" id="author" name="author" placeholder="Authors name" value="' . $author . '" required>
          </div>
        </div>
        <div class="form-group">
          <label for="content" class="col-sm-2 col-md-3 col-lg-4 control-label">Content</label>
          <div class="col-sm-9 col-md-7 col-lg-5">
            <textarea class="form-control" id="content" name="content" rows="9" placeholder="Your Message" required>' . $content . '</textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-2 col-sm-offset-2 col-md-offset-3 col-lg-offset-4">'.$this->button('Submit', 'submit', 'primary').'
          </div>
        </div>
        <input type="hidden" name="p" value="' . $this->g->in['p'] . '">
        <input type="hidden" name="a" value="' . $this->g->in['a'] . '">
        <input type="hidden" name="i" value="' . $this->g->in['i'] . '">
      </form>';
    }

    // Users

    public function veto_users_list(string $str) : string
    {
        return '
      <div class="responsive">
        <table class="table">' . $str . '
        </table>
      </div>';
    }

    public function veto_users_item(array $ary) : string
    {
        extract($ary);
        return '
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-1 col-xs-4"><label>ID</label></div>
        <div class="col-md-3 col-xs-8">' . $id . '</div>
        <div class="col-md-4"></div>
      </div>
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-1 col-xs-4"><label>UID</label></div>
        <div class="col-md-3 col-xs-8">' . $uid . '</div>
        <div class="col-md-4"></div>
      </div>
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-1 col-xs-4"><label>FirstName</label></div>
        <div class="col-md-3 col-xs-8">' . $fname . '</div>
        <div class="col-md-4"></div>
      </div>
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-1 col-xs-4"><label>LastName</label></div>
        <div class="col-md-3 col-xs-8">' . $lname . '</div>
        <div class="col-md-4"></div>
      </div>
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-1 col-xs-4"><label>Email</label></div>
        <div class="col-md-3 col-xs-8">' . $email . '</div>
        <div class="col-md-4"></div>
      </div>
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-1 col-xs-4"><label>Updated</label></div>
        <div class="col-md-3 col-xs-8">' . $updated . '</div>
        <div class="col-md-4"></div>
      </div>
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-1 col-xs-4"><label>Created</label></div>
        <div class="col-md-3 col-xs-8">' . $created . '</div>
        <div class="col-md-4"></div>
      </div>
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-1 col-xs-4"><label>Note</label></div>
        <div class="col-md-3 col-xs-8"><em>' . nl2br($anote) . '</em></div>
        <div class="col-md-4"></div>
      </div>
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4 col-xs-12 text-right">
          <br>
          '.$this->a('?p=users&a=update&i='.$id, 'Edit', 'btn btn-primary').'
          '.$this->a('?p=users&a=delete&i='.$id, 'Delete', 'btn btn-danger', ' onClick="javascript: return confirm(\'Are you sure you want to remove '.$id.'?\')"').'
        </div>
        <div class="col-md-4"></div>
      </div>';
    }

    public function veto_users_form(array $ary) : string
    {
        extract($ary);
        return '
      <form class="form-horizontal" role="form" method="post"">
        <div class="form-group">
          <label for="uid" class="col-sm-2 col-md-3 col-lg-4 control-label">UID</label>
          <div class="col-sm-9 col-md-7 col-lg-5">
            <input type="text" class="form-control" id="uid" name="uid" placeholder="Username" value="' . $uid . '" required>
          </div>
        </div>
        <div class="form-group">
          <label for="fname" class="col-sm-2 col-md-3 col-lg-4 control-label">FirstName</label>
          <div class="col-sm-9 col-md-7 col-lg-5">
            <input type="text" class="form-control" id="fname" name="fname" placeholder="FirstName" value="' . $fname . '" required>
          </div>
        </div>
        <div class="form-group">
          <label for="lname" class="col-sm-2 col-md-3 col-lg-4 control-label">LastName</label>
          <div class="col-sm-9 col-md-7 col-lg-5">
            <input type="text" class="form-control" id="lname" name="lname" placeholder="LastName" value="' . $lname . '" required>
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-2 col-md-3 col-lg-4 control-label">Email</label>
          <div class="col-sm-9 col-md-7 col-lg-5">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" value="' . $email . '" required>
          </div>
        </div>
        <div class="form-group">
          <label for="anote" class="col-sm-2 col-md-3 col-lg-4 control-label">Note</label>
          <div class="col-sm-9 col-md-7 col-lg-5">
            <textarea class="form-control" id="anote" name="anote" rows="3" placeholder="Admin Note">' . $anote . '</textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-2 col-sm-offset-2 col-md-offset-3 col-lg-offset-4">'
          .$this->button('Save', 'submit', 'primary').'
          </div>
        </div>
        <input type="hidden" name="i" value="' . $this->g->in['i'] . '">
      </form>';
//        <input type="hidden" name="p" value="' . $this->g->in['p'] . '">
//        <input type="hidden" name="a" value="' . $this->g->in['a'] . '">
    }

}
