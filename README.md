# Plugin API for Tt Sync


[![Build Status](https://travis-ci.org/fwolf/tt-sync-plugin-api.svg?branch=master)](https://travis-ci.org/fwolf/tt-sync-plugin-api)
[![Latest Stable Version](https://poser.pugx.org/fwolf/tt-sync-plugin-api/v/stable)](https://packagist.org/packages/fwolf/tt-sync-plugin-api)
[![License](https://poser.pugx.org/fwolf/tt-sync-plugin-api/license)](https://packagist.org/packages/fwolf/tt-sync-plugin-api)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/96a869bb-8a09-46df-8061-e721d13d9cff/mini.png)](https://insight.sensiolabs.com/projects/96a869bb-8a09-46df-8061-e721d13d9cff)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fwolf/tt-sync-plugin-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fwolf/tt-sync-plugin-api/?branch=master)



## PluginFactoryInterface & AbstractPluginFactory class

Each plugin should have their own factory class implement
`PluginFactoryInterface`, and implement plugin config after created.



## PluginInterface & AbstractPlugin class

All plugins should implement `PluginInterface`, `AbstractPlugin` is an implement
include shared feature.



## MessageInterface & Message class

Messages retrieved from source will be format to `MessageInterface` instance,
and transfer to destination, this interface is a pipe between different message
format from different twitter like service.

`Message` is an implement, with message dump and compare feature, can be used to
save message and attachment to disk or sort them for re-post.



## AttachmentInterface Attachment class

Interface for attachment, download feature included.



## License

Distribute under the MIT license.
