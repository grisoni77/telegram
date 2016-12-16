<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 13/12/2016
 * Time: 11:55
 */

namespace Gr77\Telegram\Message\Content;


use Gr77\Telegram\BaseObject;

class LocaleText extends BaseObject implements Text
{
    private $text;
    private $language;
    public static $allowed_languages = [
        'english',
        'italian',
    ];

    public function __construct($text, $language)
    {
        $this->text = new PlainText($text);
        if (!in_array($language, self::$allowed_languages)) {
            throw new \InvalidArgumentException(sprintf('The following language is not enabled: %s', $language), 400);
        }
        $this->language = $language;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->getText();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getText();
    }


    /**
     * @return string
     */
    public function getText()
    {
        $text = $this->text->getText();
        return $text;
    }

    /**
     * @return null|string
     */
    public function getParseMode()
    {
        return $this->text->getParseMode();
    }
}