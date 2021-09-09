<?php
namespace Parsedowns;

use Exceptions\NoConfigInfoInConfigJsonException;
use View\View;

class ConfigureParse extends \Parsedown
{
    private $config;
    private $configure;

    public function __construct($config, $configure)
    {
        $this->config=$config;
        $this->configure = $configure;
        $this->InlineTypes['{'][]= 'Input';

        $this->inlineMarkerList .= '{';
    }

    protected function inlineInput($excerpt)
    {
        if (preg_match('/^{input=(\w+)}/', $excerpt['text'], $matches)) {
            return [

                // How many characters to advance the Parsedown's
                // cursor after being done processing this tag.
                'extent' => strlen($matches[0]),
                'element' => [
                    'name' => 'div',
                    'handler' => 'plain',
                    'text' => $matches[1],
                ],
            ];
        }
    }
    protected function plain($name)
    {
        if (!@$this->config[$name]) {
            throw new NoConfigInfoInConfigJsonException();
        }
        return View::view('inputs/'.$this->config[$name]['type'])
            ->with('name', $name)
            ->with('default', @$this->configure[$name]?:$this->config[$name]['default'])
            ->with('config', $this->config[$name])
            ->render();
    }
}
