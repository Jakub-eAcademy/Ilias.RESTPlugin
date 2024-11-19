# ILIAS REST Plugin

This is a plugin for the [ILIAS Learning Management System](<http://www.ilias.de>), which provides a customizable REST API. It is a fork of an open-source project developed by fluxlabs ag, located in Burgdorf, Switzerland (https://fluxlabs.ch).

## Warning
This is a modified version of the original [REST plugin](https://github.com/hrz-unimr/Ilias.RESTPlugin) 
which contains changes required by the IACUBUS mobile application.

## Requirements
* Version: ILIAS 8
* PHP 7.0 - 7.4

## Installation

*   From within your ILIAS directory:

```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
git clone https://github.com/Jakub-eAcademy/Ilias.RESTPlugin.git REST
```

*   Update and active REST-Plugin using the drop-down action-menu button
*   Open ILIAS Administration &gt; Plugins from the drop-down menu

## Features:

*   Permission management for resources depending on REST clients using API-Keys
*   Full OAuth 2.0 support (see [RFC6749](<http://tools.ietf.org/html/rfc6749>)) including the grant types:
    *   Authorization Code
    *   Implicit
    *   Resource Owner Password Credentials
    *   Client Credentials
*   CRUD (Create-Read-Update-Delete) principle for resources
*   Easy integration of new REST endpoints possible
*   Based on the PHP SLIM Framework
*   Tools included (IShell, System Client, API Testing, IScenarios)

Note: Please refer to the [wiki](https://github.com/hrz-unimr/Ilias.RESTPlugin/wiki). pages for further information.

## Example
**Retrieve all available routes**

```bash
curl -X GET https://<your_domain>/Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/REST/api.php/v2/util/routes
```

More examples can be found in the [wiki](https://github.com/hrz-unimr/Ilias.RESTPlugin/wiki/Examples).

## License
This project is licensed under the GNU GPLv3 License - see the LICENSE.md file for details.
