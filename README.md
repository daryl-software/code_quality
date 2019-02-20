# Code quality tools

### Install composer deps in your project
```bash
composer require --dev squizlabs/php_codesniffer
```

#### Setup
- add project as git submodule relative (from gitlab) to your project
     ```
     # file: .gitmodules example for dating/fusion project
     [submodule "_code_quality"]
        	path = _code_quality
        	url = git@github.com:ezweb/code_quality.git
     ```
- you can also call hooks provided in your project 
    ```
    # .hooks/pre-commit    
    ./_code_quality/git-hooks/php-pre-commit # call
    ```
     
     
#### Configure/extend local phpcs configuration
- add/edit project `ruleset.xml`
    ```xml
    <?xml version="1.0"?>
    <ruleset>
        <description>Fusion</description>
        
        <config name="installed_paths" value="_code_quality/phpcs" />
        <rule ref="2LM"/>
      
        <!-- Override rule here -->
    </ruleset>
    ``` 
    
    
    
#### Configure gitlab-ci jobs

```
# .gitlab-ci.yml
phplint7:
    stage: lint
    image: php:7.3-alpine
    before_script:
        - apk add git
        - git submodule sync --recursive
        - git submodule update --init --recursive
    script:
        - ./_code_quality/phplint.sh

phpcs:
    stage: lint
    image: texthtml/phpcs:3.3.2
    before_script:
        - apk add git
        - git submodule sync --recursive
        - git submodule update --init --recursive
    script:
        - ./_code_quality/phpcs.sh
```