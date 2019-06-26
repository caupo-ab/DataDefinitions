<?php
/**
 * Data Definitions.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2016-2019 w-vision AG (https://www.w-vision.ch)
 * @license    https://github.com/w-vision/ImportDefinitions/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace WVision\Bundle\DataDefinitionsBundle\Interpreter;

use CoreShop\Component\Registry\ServiceRegistryInterface;
use WVision\Bundle\DataDefinitionsBundle\Model\DataSetAwareInterface;
use WVision\Bundle\DataDefinitionsBundle\Model\DataSetAwareTrait;
use WVision\Bundle\DataDefinitionsBundle\Model\DefinitionInterface;
use WVision\Bundle\DataDefinitionsBundle\Model\Mapping;
use Pimcore\Model\DataObject\Concrete;
use Webmozart\Assert\Assert;

final class IteratorInterpreter implements InterpreterInterface, DataSetAwareInterface
{
    use DataSetAwareTrait;

    /**
     * @var ServiceRegistryInterface
     */
    private $interpreterRegistry;

    /**
     * @param ServiceRegistryInterface $interpreterRegistry
     */
    public function __construct(ServiceRegistryInterface $interpreterRegistry)
    {
        $this->interpreterRegistry = $interpreterRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function interpret(Concrete $object, $value, Mapping $map, $data, DefinitionInterface $definition, $params, $configuration)
    {
        if (null === $value) {
            return [];
        }
        Assert::isArray($value, 'IteratorInterpreter can only be used with array values');

        $interpreter = $configuration['interpreter'];
        $interpreterObject = $this->interpreterRegistry->get($interpreter['type']);

        if ($interpreterObject instanceof DataSetAwareInterface) {
            $interpreterObject->setDataSet($this->getDataSet());
        }

        foreach ($value as &$val) {
            $val = $interpreterObject->interpret($object, $val, $map, $data, $definition, $params, $interpreter['interpreterConfig']);
        }

        return $value;
    }
}

class_alias(IteratorInterpreter::class, 'ImportDefinitionsBundle\Interpreter\IteratorInterpreter');
