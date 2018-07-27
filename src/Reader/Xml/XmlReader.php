<?php declare(strict_types=1);

namespace FGhazaleh\Reader\Xml;

use FGhazaleh\Exceptions\FileNotFoundException;
use FGhazaleh\Exceptions\XmlParserException;
use FGhazaleh\Reader\Contracts\ReaderInterface;
use FGhazaleh\Support\Collection\Collection;
use FGhazaleh\Support\Helpers\Helper;
use Hobnob\XmlStreamReader\Parser;

class XmlReader implements ReaderInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Read data by provided file.
     *
     * @param string $file
     * @return Collection
     * @throws FileNotFoundException
     */
    public function read(string $file):Collection
    {
        if (!Helper::fileExists($file)) {
            throw new FileNotFoundException(sprintf(
                'File not found in provided path [%s]',
                $file
            ));
        }

        return $this->parse(
            $file,
            $this->options['xml_element_filter']
        )->getData();
    }

    /**
     * Start the parsing by using XML Sax parser.
     *
     * @param string $filePath
     * @param string|null $rootElement
     * @return XmlReader
     */
    private function parse(string $filePath, string $rootElement = null):XmlReader
    {
        $xmlParser = new Parser();
        try {
            $xmlParser->registerCallback(
                $rootElement??'/',
                function (Parser $parser, \SimpleXMLElement $node) {
                    $this->children($node->children());
                }
            );

            $file = fopen($filePath, 'r');
            $xmlParser->parse($file);
            fclose($file);
        } catch (\Exception $e) {
            throw new XmlParserException($e->getMessage(), $e->getCode(), $e);
        }
        return $this;
    }

    /**
     * @todo list: Must handle the nested children.
     *
     * Add Xml node to data array
     *
     * @param \SimpleXMLElement $node
     */
    private function children(\SimpleXMLElement $node):void
    {
        $row = [];
        foreach ($node as $item) {
            //create array key.
            $key = strtolower((string)$item->getName());
            //create array item with current value.
            $row[$key] = (string)$item;
        }
        array_push($this->data, $row);
    }

    /**
     * @return Collection
     * */
    private function getData():Collection
    {
        return Collection::make($this->data);
    }
}
