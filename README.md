<!-- @format -->

# ISHDI Drupal Headless :raised_hand:

# Getting started

This repository contains the code for the ishdi_headless Drupal module. The ishdi_headless module will help to create component-based APIs for the front end using paragraphs and nodes.

Most of the Drupal approaches struggle to give well-formatted content to the front-end framework. This will be a big burden to use Drupal with front-end frameworks. The module will give a solution to create your own paragraph components and format the JSON data for the front end.

### Installation

- Download the module and paste it to /web/modules folder
- Install it like normal drupal module

### How to create pages

- The module will gives a content type called `api`. Using this we can create api pages with path
- if path is /home the API call from front end should be {your domain}/ish/v1/page?path=/home

### How to create components formatting

- Create component using paragraphs from drupal backend
- Attached this paragraph to `api` node components field
- Create a module your_module_name and add your component render service as below on your module.services.yml file

```yml
services:
    your_module_name.component_standard_text:
        class: Drupal\your_module_name\Component\StandardText
        parent: ishdi_headless.component_base
        tags:
            - { name: ish_component, type: ish_level0_standard_text }
```

- Replace ish_level0_standard_text with your paragraph machine name
- Create render class and add render() method to format your component

### Sample Code

- Sample code can be found on ishdi_headless/modules/ish_example_component
- You can enable this module to see how built-in standard_text component working
