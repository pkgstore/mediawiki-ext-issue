<?php

namespace MediaWiki\Extension\PkgStore;

use MWException;
use Parser, PPFrame, OutputPage, Skin;

/**
 * Class MW_EXT_Issue
 */
class MW_EXT_Issue
{
  /**
   * Get issue.
   *
   * @param $issue
   *
   * @return array
   */
  private static function getData($issue): array
  {
    $issue = MW_EXT_Kernel::getYAML(__DIR__ . '/store/' . $issue . '.yml');
    return $issue ?? [] ?: [];
  }

  /**
   * Get issue id.
   *
   * @param $issue
   *
   * @return string
   */
  private static function getID($issue): string
  {
    $issue = self::getData($issue) ? self::getData($issue) : '';
    return $issue['id'] ?? '' ?: '';
  }

  /**
   * Get issue content.
   *
   * @param $issue
   *
   * @return string
   */
  private static function getContent($issue): string
  {
    $issue = self::getData($issue) ? self::getData($issue) : '';
    return $issue['content'] ?? '' ?: '';
  }

  /**
   * Get issue category.
   *
   * @param $issue
   *
   * @return string
   */
  private static function getCategory($issue): string
  {
    $issue = self::getData($issue) ? self::getData($issue) : '';
    return $issue['category'] ?? '' ?: '';
  }

  /**
   * Register tag function.
   *
   * @param Parser $parser
   *
   * @return void
   * @throws MWException
   */
  public static function onParserFirstCallInit(Parser $parser): void
  {
    $parser->setFunctionHook('issue', [__CLASS__, 'onRenderTag'], Parser::SFH_OBJECT_ARGS);
  }

  /**
   * Render tag function.
   *
   * @param Parser $parser
   * @param PPFrame $frame
   * @param array $args
   *
   * @return string
   */
  public static function onRenderTag(Parser $parser, PPFrame $frame, array $args): string
  {
    // Out HTML.
    $outHTML = '<div class="mw-issue navigation-not-searchable mw-box"><div class="mw-issue-body">';
    $outHTML .= '<div class="mw-issue-icon"><div><i class="fas fa-wrench"></i></div></div>';
    $outHTML .= '<div class="mw-issue-content">';
    $outHTML .= '<div class="mw-issue-title">' . MW_EXT_Kernel::getMessageText('issue', 'title') . '</div>';
    $outHTML .= '<div class="mw-issue-list">';
    $outHTML .= '<ul>';

    foreach ($args as $arg) {
      $type = MW_EXT_Kernel::outNormalize($frame->expand($arg));

      if (!self::getData($type)) {
        $outHTML .= '<li>' . MW_EXT_Kernel::getMessageText('issue', 'error') . '</li>';
        $parser->addTrackingCategory('mw-issue-error-category');
      } else {
        $outHTML .= '<li>' . MW_EXT_Kernel::getMessageText('issue', self::getContent($type)) . '</li>';
        $parser->addTrackingCategory(self::getCategory($type));
      }
    }

    $outHTML .= '</ul></div></div></div></div>';

    // Out parser.
    return $parser->insertStripItem($outHTML, $parser->getStripState());
  }

  /**
   * Load resource function.
   *
   * @param OutputPage $out
   * @param Skin $skin
   *
   * @return void
   */
  public static function onBeforePageDisplay(OutputPage $out, Skin $skin): void
  {
    $out->addModuleStyles(['ext.mw.issue.styles']);
  }
}
