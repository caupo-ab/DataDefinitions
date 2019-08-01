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

pimcore.registerNS('pimcore.plugin.datadefinitions.provider.raw');
pimcore.registerNS('pimcore.plugin.importdefinitions.provider.raw');

pimcore.plugin.datadefinitions.provider.raw = Class.create(pimcore.plugin.datadefinitions.provider.abstractprovider, {
    getItems : function () {
        return [{
            xtype : 'textarea',
            fieldLabel : t('data_definitions_data_object_headers'),
            name : 'headers',
            grow : true,
            anchor : '100%',
            minHeight : 300,
            value : this.data['headers'] ? this.data.headers : ''
        }];
    }
});

pimcore.plugin.importdefinitions.provider.raw = Class.create(pimcore.plugin.datadefinitions.provider.raw, {});
