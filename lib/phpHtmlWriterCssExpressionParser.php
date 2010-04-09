<?php

/**
 * Responsible to parse CSS expressions like "div#my_id.my_class"
 *
 * @author    Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @license   MIT License
 */
class phpHtmlWriterCssExpressionParser
{

  /**
   * parse a CSS expression and return the HTML tag and attributes
   *
   * @param   string    $string       the CSS expression like "div#my_id.my_class"
   * @return  array                   array(string HTMl tag, array HTML attributes)
   */
  public function parse($expression)
  {
    $expression = $this->cleanExpression($expression);
    
    return array($this->parseTag($expression), $this->parseAttributes($expression));
  }

  protected function cleanExpression($expression)
  {
    if(!is_string($expression))
    {
      throw new InvalidArgumentException('The CSS expression must be a string, '.gettype($expression).' given');
    }
    
    $originalExpression = $expression;
    
    $expression = preg_replace('/^([\w|-|\#|\.]+)/i', '$1', trim($originalExpression));

    if (empty($expression))
    {
      throw new InvalidArgumentException('The CSS expression "'.$originalExpression.'" is not valid');
    }

    return $expression;
  }

  protected function parseTag($expression)
  {
    if('#' !== $expression{0} && '.' !== $expression{0})
    {
      preg_match('/^([^\#|\.]+)/', $expression, $result);

      if (isset($result[1]))
      {
        return $result[1];
      }
    }

    return null;
  }

  protected function parseAttributes($expression)
  {
    $attributes = array();

    // fetch the id
    if (false !== strpos($expression, '#'))
    {
      preg_match('/#([\w\-]*)/', $expression, $result);

      if (isset($result[1]))
      {
        $attributes['id'] = $result[1];
      }
    }
    
    // fetch the classes
    if(false !== strpos($expression, '.'))
    {
      preg_match_all('/\.([\w\-]+)/', $expression, $result);

      $attributes['class'] = implode(' ', $result[1]);
    }

    return $attributes;
  }

}