# Code quality tools

#### Setup
- add me as git submodule relative (from gitlab) to your project
     ```
     # file: .gitmodules example for dating/fusion project
     [submodule "_code_quality"]
        	path = _code_quality
        	url = ../../global/code_quality.git
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