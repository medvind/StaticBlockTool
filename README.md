# Medvind_StaticBlockTool
*This document was last updated on September 22, 2014 by Péter Tóth <peter@petertoth.se>*

## About this extension
This extension allows you to export static blocks from the Magento admin grid in the following formats:
	
* SQL
* JSON
* Magento migration file


### A note on SQL exports
When exporting as SQL, the block id is preserved. If the target database already contains static blocks, duplicate key errors might occur. If you are not importing the SQL into a clean Magento installation, it is safer to use JSON or migrations.
