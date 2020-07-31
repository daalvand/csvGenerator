<?php


namespace Daalvand\CsvGenerator;


use App\Services\CsvGenerator\Exceptions\CsvGeneratorException;

class Service implements ServiceInterface
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

    /**
     * Set if a BOM has to be added to the file
     *
     * @param bool $shouldAddBOM
     * @return $this
     */
    public function setShouldAddBOM(bool $shouldAddBOM):ServiceInterface
    {
        $this->shouldAddBOM = $shouldAddBOM;
        return $this;
    }

    /**
     * Sets the field enclosure for the CSV
     *
     * @param string $enclosure Character that enclose fields (one character only)
     * @return $this
     */
    public function setEnclosure(string $enclosure)
    {
        $this->enclosure =  $enclosure;
        return $this;
    }


    /**
     * Sets the field delimiter for the CSV
     *
     * @param string $delimiter Character that delimits fields (one character only)
     * @return $this
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setFileName(string $name):ServiceInterface
    {
        $this->fileName = $name;
        return $this;
    }

    /**
     * @param string $path
     * @return Service
     */
    public function setFilePath(string $path)
    {
        $this->filePath = $path;
        return $this;
    }


    /**
     * @param bool $append
     * @return Service
     */
    public function shouldAppend(bool $append)
    {
        $this->append = $append;
        return $this;
    }

    /**
     * Opens the CSV streamer and makes it ready to accept data.
     * @return $this
     * @throws CsvGeneratorException
     */
    public function openGenerator():ServiceInterface
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

    /**
     * assoc array contains field names -> ['id'=> 1, 'name' => 'ali', 'job' => ['id' => 1, 'name' => 'developer']]
     * @param array $row
     * @return $this
     */
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

    /**
     * @return $this
     * @throws CsvGeneratorException
     */
    public function close():ServiceInterface
    {
        if(!fclose($this->file)){
            throw new CsvGeneratorException();
        }
        return $this;
    }

    /**
     * @param Mapper $mapper
     * @return $this
     */
    public function setMapper(Mapper $mapper): ServiceInterface
    {
        $this->mapper = $mapper;
        return $this;
    }


    /**
     * @inheritDoc
     */
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