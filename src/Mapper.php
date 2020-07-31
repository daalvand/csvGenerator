<?php


namespace Daalvand\CsvGenerator;


use Daalvand\CsvGenerator\Support\Helpers;
use Daalvand\CsvGenerator\Support\Str;
use Daalvand\CsvGenerator\Support\Arr;

class Mapper
{
    private $headers;
    private $mappings;

    /**
     * @param string $mappedField
     * @param string $original
     * @param $type
     * @return Mapper
     */
    public function setHeader(string $mappedField, string $original, string $type = null): self
    {
        $this->headers[$mappedField] = [
            'from' => $original,
            'type' => $type
        ];
        return $this;
    }

    /**
     * @param bool $split
     * @return $this
     */
    public function shouldSplitDates(bool $split = false)
    {
        $this->splitDate = $split;
        if(!empty($this->mappings)){
            $this->setMappings($this->mappings);
        }
        return $this;
    }

    /**
     * set headers -> with mapped fields
     * [
     *      'mappedField1' => [
     *           'from' => 'field1',
     *           'type' => 'array'
     *       ],
     *      'mappedField2' => [
     *          'from' => 'field2',
     *          'type' => 'string'
     *      ]
     * ]
     * @param array $headers
     * @return Mapper
     */
    public function setHeaders(array $headers): self
    {
        if (Arr::isAssoc($headers)) {
            $this->headers = $headers;
        } else {
            $this->headers = [];
            foreach ($headers as $header) {
                $this->headers[$header] = ['from' => $header];
            }
        }
        return $this;
    }

    /**
     * for nested complex data this method is useful
     * @param array $mappings
     * @return Mapper
     */
    public function setMappings(array $mappings): self
    {
        $this->mappings = $mappings;
        $this->headers = $this->convertMappingToHeaders($mappings);
        return $this;
    }

    /**
     * convert mapping to csv mapped headers
     * @param array $mappings
     * @param string $mappedPrepend
     * @param string $fieldPrepend
     * @param bool $plural
     * @return array
     */
    private function convertMappingToHeaders(array $mappings, $mappedPrepend = '', $fieldPrepend = '', $plural = false)
    {
        $results = [];
        foreach ($mappings as $field => $mapping) {
            $mappedField = $plural ? Str::plural($field) : $field;
            if (isset($mapping['type']) && $mapping['type'] === 'array' && isset($mapping['items'])) {
                $results = array_merge($results, $this->convertMappingToHeaders($mapping['items'], $mappedPrepend . $mappedField . '.', $fieldPrepend . $field . '.*.', true));
            } elseif (isset($mapping['type']) && $mapping['type'] === 'object') {
                $results = array_merge($results, $this->convertMappingToHeaders($mapping['items'], $mappedPrepend . $mappedField . '.', $fieldPrepend . $field . '.'));
            }else{
                $results[$mappedPrepend . $mappedField] = ['from' => $fieldPrepend . $field];
            }
        }
        return $results;
    }

    /**
     * @return array
     */
    public function getMappings(): array
    {
        return $this->mappings;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getRow(array $row)
    {
        $result = [];
        foreach ($this->headers as $mappedField => $value) {
            $result[$mappedField] = Helpers::dataGet($row, $value['from']);
            if($result[$mappedField] && isset($value['action'])){
                $result[$mappedField] = $this->getValueByAction($value['action'], $result[$mappedField]);
            }elseif (is_array($result[$mappedField])) {
                $result[$mappedField] = Arr::isAssoc($result[$mappedField]) ? null : implode(', ', $result[$mappedField]);
            }
        }
        return $result;
    }

    /**
     * make value by action
     * @param $callback
     * @param $value
     * @return array
     */
    private function getValueByAction($callback, $value)
    {
        [$class, $method] = Str::parseCallback($callback);
        if (class_exists($class) && method_exists($class, $method)) {
            $value = (new $class)->$method($value);
        }
        return $value;
    }
}