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
 * @license    https://github.com/w-vision/DataDefinitions/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Wvision\Bundle\DataDefinitionsBundle\Setter;

use Pimcore\Model\DataObject\Concrete;
use Wvision\Bundle\DataDefinitionsBundle\Model\ImportMapping;
use Pimcore\Model\DataObject\Data\ImageGallery;
use Pimcore\Model\Asset\Image;
use Pimcore\Model\DataObject\Data\Hotspotimage;

class ImageGallerySetter implements SetterInterface
{
    public function set(Concrete $object, $value, ImportMapping $map, $data)
    {
        $fieldName = $map->getToColumn();
        $getter = sprintf('get%s', ucfirst($fieldName));
        $setter = sprintf('set%s', ucfirst($fieldName));
                
        if (!is_iterable($value)) {
            $value = [$value];
        }
        
        /** @var ImageGallery $gallery */
        $gallery = $object->$getter();
        if (!$gallery) {
            $gallery = new ImageGallery([]);
        }

        $items = $gallery->getItems();
        
        // Find unique key (path) for all existing items
        $existingKeys = [];
        foreach ($items as $existingItem) {
            $existingKeys[] = (string)$existingItem;
        }

        foreach ($value as $asset) {
            // only images and hot spot images can be added
            if ($asset instanceof Image) {
                $hotspot = new Hotspotimage();
                $hotspot->setImage($asset);
            }
            else if ($asset instanceof Hotspotimage) {
                $hotspot = $asset;
            } else {
                continue;
            }

            // add image if it does not already exist in gallery
            $newKey = (string)$hotspot;
            if (!in_array($newKey, $existingKeys)) {
                $items[] = $hotspot;
            }
        }

        $gallery->setItems($items);
        $object->$setter($gallery);
    }

}


