<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <body>
        <form method="get" action="audit_tool.php">
          <input type="text" name="domain" />
          <input type="submit" name="submit" value="submit" />
        </form>

<?php
  if (isset($_GET['domain'])) {
    //$html = file_get_contents($_GET['domain']);
    $dom = new DOMDocument;
    @$dom->loadHTMLFile($_GET['domain']);
    $seo = array();
    $titles = $dom->getElementsByTagName('title');
    if ( count($titles) > 0 ) {
      echo '<h2>Title Tag(s):</h2>';
      $seo['titles']['heading'] = 'Title Tag(s):';
      foreach($titles as $title) {
        if ($title->nodeValue != '') {
          echo $title->nodeValue, PHP_EOL;
          $seo['titles']['content'][] = $title->nodeValue;
        } else {
          echo '<p>There was no page title.</p>';
          $seo['titles']['error'] = '<p>There was no page title.</p>';
        }
      }
    }
    $descriptions = $dom->getElementsByTagName('meta');
    if ( count($descriptions) > 0 ) {
      echo '<h2>Meta Description(s):</h2>';
      $seo['descriptions']['heading'] = 'Meta Description(s):';
      foreach($descriptions as $description) {
        if ($description->getAttribute('name') == 'description') {
          if ($description->getAttribute('content') != '') {
            echo $description->getAttribute('content'), PHP_EOL;
            $seo['descriptions']['content'][] = $description->getAttribute('content');
          } else {
            echo '<p>There was no meta description.</p>';
            $seo['descriptions']['error'] = '<p>There was no meta description.</p>';
          }
        }
      }
    }
    $h1s = $dom->getElementsByTagName('h1');
    if ( count($h1s) > 0 ) {
      echo '<h2>H1 Tag(s):</h2>';
      $seo['h1s']['heading'] = 'H1 Tag(s):';
      foreach($h1s as $h1) {
        if (is_null($h1->nodeValue)) {
          echo $h1->nodeValue, PHP_EOL;
          $seo['h1s']['content'][] = $h1->nodeValue;
        } else {
          echo '<p>There were no h1 tags.</p>';
          $seo['h1s']['error'] = '<p>There were no h1 tags.</p>';
        }
      }
    }
    $imgs = $dom->getElementsByTagName('img');
    if ( count($imgs) > 0 ) {
      echo '<h2>Image Alt(s):</h2>';
      $seo['imgs']['heading'] = 'Image Alt(s):';
      foreach($imgs as $img) {
        if ($img->getAttribute('alt') != '') {
          echo $img->getAttribute('src') . ': <strong>' . $img->getAttribute('alt') . "</strong><br />";
          $seo['imgs']['content']['src'][] = $img->getAttribute('src');
          $seo['imgs']['content']['alt'][] = $img->getAttribute('alt');
        } else {
          echo '<p>' . $img->getAttribute('src') . " didn't have alt text.</p>";
          $seo['imgs']['error'][] = '<p>' . $img->getAttribute('src') . " didn't have alt text.</p>";
        }
      }
    } else {
      echo '<p>There were no images</p>';
    }
    echo '<h2>Robots:</h2>';
    $seo['robots']['heading'] = 'Robots:';
    $robots_url = $_GET['domain'] . '/robots.txt';
    $robots = file_get_contents($robots_url);
    if ($robots != '') {
      echo '<p>Robots was found at <a href="' . $robots_url . '" target="_blank">Robots</a></p><br />';
      echo $robots;
      $seo['robots']['content'] = true;
      $seo['robots']['file'] = $robots;
    } else {
      echo '<p>There was no robots.txt found.</p>';
      $seo['robots']['error'] = '<p>There was no robots.txt found.</p>';
    }
    echo '<h2>Sitemap</h2>';
    $seo['sitemap']['heading'] = 'Sitemap';
    $sitemap_url = $_GET['domain'] . '/sitemap.xml';
    $sitemap = file_get_contents($sitemap_url);
    if($sitemap != ''){
      echo 'Sitemap was found at <a href="' . $sitemap_url . '" target="_blank">Sitemap</a><br />';
      $seo['sitemap']['content'] = true;
      $seo['sitemap']['file'] = $sitemap;
    } else {
      echo "<p>There was no sitemap.xml file found.</p>";
      $seo['sitemap']['error'] = "<p>There was no sitemap.xml file found.</p>";
    }
    print_r($seo);
    foreach ($seo as $type) {
      echo '<h2>' . $type['heading'] . '</h2>';
      switch($type) {
        case 'titles' || 'descriptions' || 'h1s':
          if (isset($type['content'])) {
            foreach ($type['content'] as $m) {
              echo $m;
            }
          } else {
            echo $type['error'];
          }
          break;
        case 'imgs':
          for ($i = 0; $i < count($type['src']); $i++) {
            echo $type['src'] . ' <strong>' . $type['alt'] . '</strong>';
          }
          foreach($type['error'] as $err) {
            echo $err;
          }
          break;
        case 'robots' || 'sitemap':
          if ($type['content']) {
            echo '<p>' . $type . ' exists.</p>';
          }
          break;
      }
    }
  }
?>
    </body>
</html>