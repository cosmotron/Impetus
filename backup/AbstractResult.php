/**
 * AbstractResult
 */
abstract class AbstractResult {
    var $results;
    var $format;
    var $csvName;
    var $templateName;

    public function __construct($results, $format, $csvName, $templateName) {
        $this->results = $results;
        $this->format = $format;
        $this->csvName = $csvName;
        $this->templateName = $templateName;
    }

    abstract protected function generateCsvResponse();
    abstract protected function generateHtmlResponse();

    public function generate() {
        if (!$this->results) {
            throw $this->createNotFoundException('No results found for year '.$this->get('session')->get('academic_year'));
        }

        switch ($this->format) {
            case 'csv':
                return $this->generateCsvResponse();

            case 'html':
                return $this->generateHtmlResponse();

            default:
                throw $this->createNotFoundException('Invalid format');
                break;
        }
    }
}

/**
 * SimpleResult
 */
class SimpleResult extends AbstractResult {
    public function __construct($results, $format, $csvName, $templateName) {
        parent::__construct($results, $format, $csvName, $templateName);
    }

    protected function generateCsvResponse() {
        $csv = $this->get('csv_service')->createSimpleTable($this->results);
        return $this->get('csv_service')->generateHttpResponse($csv, $this->csvName);
    }

    protected function generateHtmlResponse() {
        return $this->render('ImpetusAppBundle:Analytics:'.$this->templateName,
                             array('page' => 'analytics',
                                   'results' => $this->results));
    }
}