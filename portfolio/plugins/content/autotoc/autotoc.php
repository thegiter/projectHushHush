<?php
/**
 * @version        0.17.5
 * @package        Joomla Article Auto ToC
 * @subpackage     Content
 * @copyright      Copyright (C) 2009 Straton IT
 * @copyright      Copyright (C) 2010-2013 Thomas Geymayer. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 * Changes:
 *   
 *     * Reworked options:
 *       - Separate settings for enable adding numbering to the toc items and
 *         the headings itself
 *       - Set a startlevel for the heading level and ignore higher headings.
 *         Eg. set to h2 to ignore h1 headings which normally are just page
 *         headings
 *       - Removed indent option and replaced with enable/disable using the
 *         default css (Indention is part of design and belongs to css...)
 *       - Removed disable option because you are able to disable the plugin
 *         itself directly in the plugin manager
 *     * Fixes:
 *       - Removed unneccessary operations on $params in onPrepareContent
 *         because it lead to an error.
 *       - Shortened calls to params because it is a member of JPlugin and
 *         therefore doesn't need to be accessed indirectly with JPluginHelper
 *       - Made output XHTML konformant (anchor names must not start numeric)
 *
 *     -- Thomas Geymayer <tomgey@gmail.com> Sat, 06 Feb 2010 22:59:20 +0100
 *
 *     * Added an option to set the float direction of the toc.
 *
 *     -- Thomas Geymayer <tomgey@gmail.com> Sat, 21 Mar 2010 12:05:37 +0100
 *
 *     * Don't overwrite existing tocs to ensure compatibilty with the
 *       pagebreak plugin.
 *
 *     -- Thomas Geymayer <tomgey@gmail.com> Thu, 08 Apr 2010 18:33:26 +0200
 *
 *     * Also load the language file for localization in the frontend.
 *
 *     -- Thomas Geymayer <tomgey@gmail.com> Sat, 15 May 2010 00:19:58 +0200
 *
 *     * Added an option to disable generating of the ToCs by default and enable
 *       them with putting a special text inside the articles where it should be
 *       displayed.
 *
 *     -- Thomas Geymayer <tomgey@gmail.com> Thu, 27 May 2010 00:00:25 +0200
 *
 *     *** For any further changes consult the git commit log ***
 * 
 *     * Ported to Joomla! 1.6 (based on
 *        http://docs.joomla.org/Tutorial:Upgrade_Joomla_1.5_Extension_to_Joomla_1.6)
 *
 *     -- Frank Thommen <frank.thommen@gmx.net> Mon, 14 Feb 2011 16:00:00 +0200
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.plugin.plugin' );

/**
 * Display the TOC for articles with headers
 *
 * @package        Joomla
 * @subpackage     Content
 * @since          1.5
 */
class plgContentAutoToc extends JPlugin
{
    /**
     * New Joomla 1.6 Method
     */
    public function onContentPrepare($context, &$article, &$params, $limitstart)
    {
      return $this->onPrepareContent($article, $params, $limitstart);
    }
    
    /**
     * Method is called by the view
     *
     * @param     object  $article    The article object
     * @param     object  $params     The article params
     * @param     int     $limitstart The 'page' number
     */
    function onPrepareContent(&$article, &$params, $limitstart)
    {
      // I don't know why, but sometimes the plugin get's executed
      // on the admin page which produces error about the missing JSite.
      if( !JFactory::getApplication()->isSite() )
        return;

      $version = new JVersion;
      $joomla_1_5 = substr($version->getShortVersion(),0,3) == '1.5';

      $this->plugin_base_path =
        'plugins/content'.($joomla_1_5 ? '' : '/autotoc');

      if( JDEBUG )
        $this->debugPrint($version, $article);
        
      // use old config vars if exist
      foreach( array
               (
                 'enabledDefault' => 'enabled',
                 'displayToc' => 'layout'
               ) as $old_name => $new_name )
      {
        if( $this->params->get($old_name, 'exists-not') != 'exists-not' )
          $this->params->set($new_name, $this->params->get($old_name));
      }
      
      /* Match inline tag:
      
         Recommended:
           {autotoc}
           {autotoc layout=table}
           {autotoc enabled=no|layout=dropdown}
           etc.
           
         Supported for backward compatibility:
         
           <!-- ArticleToc: enabled=yes -->
           { ArticleToc: enabled=no }
           etc.
      */
      $regex = '/'
             . '(({)|(<!--))\\s*'         // Start with { or <!--
             . '(ArticleToc|autotoc)\\s*' // the tag name
             . ':?(.*)'                   // Here should be the parameters $5
             . '(?(2)}|-->)'              // The matching closing } or -->
             . '/i';

      // used for text before and after toc if toc shouldn't be placed at the
      // begin of the article
      $text_parts = null;

      // parse inline parameters inside the tag
      if( preg_match($regex, $article->text, $matches) )
      {
        // if a tag has been found it is enabled by default (No need for
        // enabled=yes).
        $this->params->set('enabled', 1);

        foreach( explode('|', $matches[5]) as $param )
        {
          if( count($param = explode('=', $param)) == 2 )
          {
            $key = trim($param[0]);
            $value = trim($param[1]);

            switch( $key )
            {
              case 'enabled':
                if( !$this->parseBool($value) )
                  $this->params->set('enabled', 0);
                break;
              case 'layout':
                $this->params->set('layout', $value);
                break;
              case 'float':
                $this->params->set('floatDir', $value);
                break;
              case 'addNumbering':
                $this->params->set('addNumbering', $this->parseBool($value));
                break;
              case 'addNumberingToc':
                $this->params->set('addNumberingToc', $this->parseBool($value));
                break;
              // case 'position':
              // TODO should we do anything here?
              //   break;
            }
          }
        }

        // get parts before and after toc and remove tag
        $text_parts = explode($matches[0], $article->text);

        // build text in case toc is disabled
        $article->text = implode($text_parts);
      }
      
      // check whether plugin has been disabled
      if(   !$this->params->get('enabled', true)
         ||  $this->params->get('intro_only')
         ||  $this->params->get('popup') )
        return true;

      // check if we are on the frontpage
      $menu = JFactory::getApplication()->getMenu();
      $lang = JFactory::getLanguage()->getTag();
      if( $menu->getActive() == $menu->getDefault($lang) )
      {
        if( !$this->params->get('showOnFrontPage', false) )
          return true;

        // on the frontpage we need to include the toc in the text, as the
        // $article->toc won't be displayed
        if( !is_array($text_parts) )
          $text_parts = array('', $article->text);
      }
      else
      {
        $view = JRequest::getCmd('view');
        if( !in_array( $view,
                       array_merge
                       (
                         array
                         (
                           'article',
                           'items',   // <- for Flexicontent
                           'item',    // <- for K2
                         ),
                         explode(',', $this->params->get('additional_views',''))
                       ) ))
          return true;
          
        if(    JRequest::getCmd('option') != 'com_content'
            || in_array
               (
                 $view,
                 explode(',', $this->params->get('additional_views',''))
               ) )
        {
          // as it seems only com_content from the Joomla core supports the
          // article->toc field, so for everything else we have to place it
          // directly into the content.
          if( !is_array($text_parts) )
            $text_parts = array('', $article->text);
        }
      }

      // get heading level range occuring in the text and limited by config
      $min_level = 7;
      $max_level = 0;
      for( $i = $this->params->get('numSlice', 1) + 1;
           $i <= $this->params->get('maxHeadingLevel', 6);
           ++$i )
      {
        if( stripos($article->text, "<h$i") !== false )
        {
          if( $i < $min_level )
            $min_level = $i;
          if( $i > $max_level )
            $max_level = $i;
        }
      }

      if( $max_level < $min_level )
        // No further processing needed without sufficient heading level
        return;

      // Build the page structure and proceed to the required content substitution
      $structure = new plgContentAutoTocArticleStructure
      (
        array
        (
          'addNumbering'    => $this->params->get('addNumbering', true),
          'addNumberingToc' => $this->params->get('addNumberingToc', false),
          'numSlice'        => $min_level - 1,
          'maxLevel'        => $max_level
        )
      );
      
      if( is_array($text_parts) )
        $structure->processArticle($text_parts);
      else
        $structure->processArticle($article->text);

      // Create the $article->toc field
      if( $this->params->get('addToc', true) )
      {
        $toc = $this->createToc($structure);

        if( is_array($text_parts) )
          $article->text = implode($toc, $text_parts);
        else
          $article->toc .= $toc;

        if( !empty($matches) )
          $article->introtext = str_replace( $matches[0], $toc,
                                             $article->introtext );
      }

      return true;
    }

    /**
     *
     *
     */
    function createToc( $structure )
    {
      $document = JFactory::getDocument();
      $this->loadLanguage('',JPATH_ADMINISTRATOR);

      if( $this->params->get('useDefaultCSS', true) )
        $document->addStyleSheet(JURI::base(true).'/'.$this->plugin_base_path.'/autotoc.css');
        
      $clear = $this->params->get('appendClear', false)
             ? '<div style="clear: both;"></div>'
             : '';

      $float = $this->params->get( 'floatDir', 'none' );
      
      if( $float === 'none' )
        $float = '';
      else
        $float = "style=\"float:$float;\"";


      switch( $this->params->get('layout', 'table') )
      {
        case 'table':
          return $this->createTableToc( $structure,
                                        $float,
                                        $this->params->get('hide_button', true),
                                        $this->params->get('hide_default', false)).$clear;

        case 'dropdown':
        case 'drop_down': // (dropdown should be used. Just legacy...)
          $document->addScript(JURI::base(true).'/'.$this->plugin_base_path.'/autotoc.js');
          return $this->createDropDownToc($structure, $float).$clear;
          
        default:
          return '<div class="error">Warning: unknown toc layout</div>';
      }
    }

    /**
     * Create the table of contents as a box
     *
     */
    function createTableToc($structure, $float, $hide_button, $hide_default)
    {
        $toc = "<div class=\"autotoc\" $float>";
        if( $hide_button )
          $toc .= '<script type="text/javascript">
            /* <![CDATA[ */
            function toc_toggle_visible(ob)
            {
              var ul = ob.parentNode.parentNode.getElementsByTagName("ul")[0];
              var d = ul.style.display == "none";
              ul.style.display = d ? "" : "none";
              ob.innerHTML = d ? "' . JText::_('AUTO_TOC_HIDE') . '"
                               : "' . JText::_('AUTO_TOC_SHOW') . '";
              return false;
            }
            /* ]]> */
            </script>';

        $toc .= '<span>' . JText::_('AUTO_TOC_CONTENTS') . '</span>';
        if( $hide_button )
          $toc .= '<span>'
                .  '[<a href="#" onclick="return toc_toggle_visible(this);">'
                .   ($hide_default ? JText::_('AUTO_TOC_SHOW')
                                   : JText::_('AUTO_TOC_HIDE'))
                .  '</a>]'
                . '</span>';
        
        $toc .= '<ul'.($hide_default ? ' style="display:none;"':'').'><li>';

        $level = 0;
        $cur_empty = true;
        
        // Don't know why the hell php 5+ uses references by default (Isn't
        // Java enough? :/)
        $uri = clone JFactory::getURI();        
        
        foreach( $structure->headings() as $heading )
        {
          while( $heading['level'] > $level )
          {
            $toc .= '<ul><li>';
            ++$level;
            $cur_empty = true;
          }
          
          while( $heading['level'] < $level )
          {
            $toc .= '</li></ul>';
            --$level;
          }

          if( !$cur_empty )
            $toc .= '</li><li>';

          $uri->setFragment($heading['anchor']);

          $link = htmlspecialchars($uri->toString());
          $title = $heading['contents'];
          $class = 'toclink toclink-h'.$heading['level'];
          
          $toc .= "<a href='$link' class='$class'>$title</a>";
          $cur_empty = false;
        }
        
        while( $level-- >= 0 )
          $toc .= '</li></ul>';
          
        $toc .= '</div>';

        return $toc;
    }

    /**
     * Create the table of contents as a dropdown list
     *
     */
    function createDropDownToc($structure, $float)
    {
        $toc = '
        <form class="autotoc" action="#" '.$float.'>
        <select name="contenttoc_menu" onchange="menu_goto( this.form )">
            <option selected="selected" value="">'. JText::_('AUTO_TOC_CONTENTS') .'</option>
        ';

        foreach ( $structure->headings() as $heading )
        {
          $toc .= '
                <option value="#'.$heading['anchor'].'">'. $heading['contents'] .'</option>';
        }

        $toc .= '</select></form>';
        
        return $toc;
    }
    
    /**
     * Print some debug information
     */
    protected function debugPrint(&$version, &$article)
    {
      echo "\n<!-- AutoToC START (view=".JRequest::getCmd('view').")-->\n\n";
      echo '<div class="autotoc-run"><b>Running autotoc</b>';
      echo "\n<!-- AutoToC - version START -->\n\n";
      var_dump($version);
      echo "\n<!-- AutoToC - version END -->\n\n";
      echo "\n<!-- AutoToC - article START -->\n\n";
      var_dump($article);
      echo "\n<!-- AutoToC - article END -->\n\n";
      echo '</div>';
      echo "\n<!-- AutoToC END -->\n\n";
    }
    
    protected function parseBool($str)
    {
      return in_array(strtolower($str), array('yes', 'true', 'on', '1'));
    }

    private $plugin_base_path;
}

/**
 * Stores the contents of the toc independend of the markup
 */
class plgContentAutoTocArticleStructure
{
  private $number, $headings;
  private $addNumbering = false;
  private $addNumberingToc = true;
  private $numSlice = 1;
  private $maxLevel = 6;
  private static $regex;
  private static $regex_matched_cleanly;
  private static $regex_matched_loosely;

  /**
   * Constructor
   */
  function __construct( $options )
  {
    $this->addNumbering = (bool)@$options['addNumbering'];
    $this->addNumberingToc = (bool)@$options['addNumberingToc'];
    $this->numSlice = (int)@$options['numSlice'];
    $this->maxLevel = (int)@$options['maxLevel'];

    if( !isset( self::$regex ) )
    {
      self::$regex = '#'
          .'( <h([1-6]) [^>]* ) >'  // <hN>, $1 is <hN attr="val"   $2 is N
          .'( [^<>]+ )'             // content $3, may not be empty or contain more tags
          .'( </h\\2> )'            // matching </hN> $4
          .'|'                      // OR: looser match below
          .'( <h([1-6]) [^>]* ) >'  // idem, $5, $6
          .'( .*? (?: \\n .*? )? )' // content $7, anything on 2 lines max
          .'( </h\\6> )'            // idem, $8
          .'#ix';

      self::$regex_matched_cleanly = 1; # $1 ..
      self::$regex_matched_loosely = 5; # $5 ..
    }
  }

  function processArticle( &$articleText )
  {
    $this->number = array(0);
    $this->headings = array();

    if( is_array($articleText) )
    {
      foreach($articleText as &$part)
        $part = preg_replace_callback
                (
                  self::$regex,
                  array( $this, 'headingReplaceCallback' ),
                  $part
                );
    }
    else
    {
      $articleText = preg_replace_callback
                     (
                       self::$regex,
                       array( $this, 'headingReplaceCallback' ),
                       $articleText
                     );
    }
  }

  /**
   * Callback for the matched headings
   */
  function headingReplaceCallback( $matches )
  {
    if( $matches[ self::$regex_matched_cleanly ] )
      $matches_index = self::$regex_matched_cleanly;
    else
      $matches_index = self::$regex_matched_loosely;

    list($opening, $level, $contents, $closing) =
      array_slice($matches, $matches_index, 4);
    $level -= $this->numSlice;
    
    // Extract text for title in ToC
    $text_contents = preg_replace('# < [^>]+ > | \\n #x', '', $contents);
    
    if(   $level < 1 || $level > $this->maxLevel - $this->numSlice
          // ignore headings without text (eg. images)
       || !strlen(trim($text_contents)) )
      return "$opening>$contents$closing";
      
    $this->incrementNumber($level);
    $num = join('.', $this->number) . '.&nbsp;';

    /* Remove tags from front and back to get first occurrence of text

    This is used to place the numbering correctly in nested tags. eg.
    
    <h2>
      <strong>
        $numbering
        <span style="color:red;">Important</span>
        Heading Text
      </strong>
    </h2>

    */    
    $front = '';
    $center = $contents;
    $back = '';
    
    while(   strlen($center)
          && $center[0] == '<'
          && $center[strlen($center) -1] == '>' )
    {
      $split_front = strpos($center, '>');
      $split_back = strrpos($center, '</', $split_front);
      
      if( $split_front === false || $split_back === 'false' )
        break;

      $front .= substr($center, 0, $split_front + 1);
      $back .= substr($center, $split_back);
      
      $center = trim(substr( $center,
                             $split_front + 1,
                             $split_back - $split_front - 1 ));
    }
    
    // Rebuild heading and include numbering
    $contents = $front.($this->addNumbering ? $num : '').$center.$back;

    $anchorMatches = array();
    if( preg_match( '/id=([\'"])([^\\1]*)\\1/i', $opening, $anchorMatches ) )
    {
      $anchor = $anchorMatches[2];

      if( strpos($contents, $num) === 0 )
        $num = ''; // prevent double numbering
    }
    else
    {
      $anchor = 'h'.join( '-', $this->number ) . '-' . JFilterOutput::stringURLSafe( $text_contents );
      $opening .= " id='$anchor'";
    }

    $this->headings[] = array
    (
      'number' => $this->number,
      'contents' => ($this->addNumberingToc ? $num : '').$text_contents,
      'anchor' => $anchor,
      'level' => $level
    );

    return "$opening>$contents$closing";
  }

  function incrementNumber( $level )
  {
    if( $level > count( $this->number ) )
    {
      $this->number = array_pad( $this->number, $level, 1 ); // New sublevel(s)
    }
    else
    {
      while( $level < count($this->number) )
        array_pop( $this->number ); // Up a level, as much as needed

      $this->number[ count( $this->number ) - 1 ]++; // increment last digit
    }
  }

  /**
   * Getter for the list of headings
   *
   * @return Array  An array of headings. Each element has the fields 'number',
   *                'contents', 'anchor' and 'level'.
   */
  function headings()
  {
    return $this->headings;
  }
}

?>
