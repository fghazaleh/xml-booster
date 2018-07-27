<?php declare(strict_types=1);

namespace FGhazaleh\Commands;

use FGhazaleh\Exceptions\FileNotFoundException;
use FGhazaleh\Reader\Contracts\ReaderInterface;
use FGhazaleh\Storage\Contracts\StorageInterface;
use FGhazaleh\Support\Helpers\Helper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class XmlParserCommand extends BaseCommand
{

    /**
     * @var ReaderInterface
     */
    private $reader;
    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(ReaderInterface $reader, StorageInterface $storage)
    {
        parent::__construct(null);
        $this->reader = $reader;
        $this->storage = $storage;
    }

    protected function configure()
    {
        $this->setName('parse')
            ->setDescription('Parse XML and push to Google Spreadsheet')
            ->addArgument('path', InputArgument::REQUIRED, 'XML full path, local or remote path.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // get the xml path from console input arg.
        $filePath = $input->getArgument('path');

        // validate if the xml path exists.
        if (!Helper::fileExists($filePath)) {
            throw new FileNotFoundException(sprintf(
                'File not found in provided path [%s]',
                $filePath
            ));
        }

        try {
            $output->writeln('<comment>This process may take time to finish, please wait...</comment>');

            $filename = $this->getFilename($filePath);

            //Reading XML file.
            $output->writeln(sprintf('<info>Reading XML file [%s].</info>', $filename));
            $data = $this->reader->read($filePath);

            //Storing data to Google SpreadSheet.
            $output->writeln(sprintf('<info>Storing data to Google SpreadSheet [%s].</info>', $filename));
            $this->storage->store($filename, $data);

            $output->writeln('<info>Done.</info>');
        } catch (\Exception $e) {
            $this->getLogger()->error($e->getMessage(), $e->getTrace());
            throw $e;
        }
    }

    /**
     * Get filename from filePath
     *
     * @param string $filePath
     * @return string
     * */
    private function getFilename(string $filePath): string
    {
        $name = basename($filePath);
        return $name;
    }
}
