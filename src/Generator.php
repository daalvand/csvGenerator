<?php


namespace Daalvand\CsvGenerator;


use Daalvand\CsvGenerator\Exceptions\CsvGeneratorException;

class Generator implements GeneratorInterface
{
    /**
     * @var false|resource
     */
    protected $file;
    protected  $fileName = '';
    protected  $filePath = '';
    protected  $shouldAddBOM = true;
    protected  $enclosure = '"';
    protected  $delimiter = ',';
    protected  $mapper;
    protected  $append = false;


    /** Definition of the BOMs for the different encodings */
    const BOM_UTF8     = "\xEF\xBB\xBF";

    public function setShouldAddBOM(bool $shouldAddBOM):GeneratorInterface
    {
        $this->shouldAddBOM = $shouldAddBOM;
        return $this;
    }

    public function setEnclosure(string $enclosure)
    {
        $this->enclosure =  $enclosure;
        return $this;
    }


    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    public function setFileName(string $name):GeneratorInterface
    {
        $this->fileName = $name;
        return $this;
    }

    public function setFilePath(string $path):GeneratorInterface
    {
        $this->filePath = $path;
        return $this;
    }


    public function shouldAppend(bool $append):GeneratorInterface
    {
        $this->append = $append;
        return $this;
    }

    public function openGenerator():GeneratorInterface
    {
        if(empty($this->mapper->getHeaders()) || empty($this->filePath) || empty($this->fileName)){
            throw new CsvGeneratorException();
        }
        $this->checkDirectory();
        $fullPath = $this->filePath . DIRECTORY_SEPARATOR . $this->fileName;
        if($this->append && file_exists($fullPath)){
            $this->file = fopen($fullPath, 'a');
        }else{
            $this->file = fopen($fullPath, 'w');
            if ($this->shouldAddBOM) {
                // Adds UTF-8 BOM for Unicode compatibility
                fputs($this->file, self::BOM_UTF8);
            }
            $this->fputcsv(array_keys($this->mapper->getHeaders()));
        }
        return $this;
    }

    public function addRow(array $row)
    {

        $row = $this->mapper->getRow($row);
        $this->fputcsv($row);
        return $this;
    }

    /**
     * add new row to csv
     * @param array $row
     */
    private function fputcsv(array $row): void
    {
        fputcsv($this->file, $row, $this->delimiter, $this->enclosure);
    }

    public function close():GeneratorInterface
    {
        if(!fclose($this->file)){
            throw new CsvGeneratorException();
        }
        return $this;
    }

    public function setMapper(Mapper $mapper): GeneratorInterface
    {
        $this->mapper = $mapper;
        return $this;
    }


    public function getMapper(): Mapper
    {
        return $this->mapper;
    }

    private function checkDirectory(): void
    {
        if (!is_dir($this->filePath)) {
            mkdir($this->filePath);
        }
    }
}