*** Settings ***
Library           SeleniumLibrary

*** Variables ***
${LOGIN_URL}      http://localhost:8000/login
${BROWSER}        chrome
${USERNAME}       allan_fontaine@outlook.com
${PASSWORD}       test

*** Test Cases ***
Valid Login
    [Documentation]    Test a valid login
    Open Browser To Login Page
    Input Username    ${USERNAME}
    Input Password    ${PASSWORD}
    Submit Login Form
    Login Should Be Successful
    Close Browser

Invalid Login
    [Documentation]    Test an invalid login
    Open Browser To Login Page
    Input Username    ${USERNAME}
    Input Password    wrongpassword
    Submit Login Form
    Login Should Fail
    Close Browser

*** Keywords ***
Open Browser To Login Page
    Open Browser    ${LOGIN_URL}    ${BROWSER}

Input Username
    [Arguments]    ${username}
    Input Text    xpath=//*[@id="username"]    ${username}

Input Password
    [Arguments]    ${password}
    Input Text    xpath=//*[@id="password"]    ${password}

Submit Login Form
    Click Button    xpath=/html/body/div[1]/div[3]/div/form/button

Login Should Be Successful
    Wait Until Page Contains Element    xpath=//h1[contains(text(),"Bienvenue sur Todo List")] 

Login Should Fail
    Wait Until Page Contains Element    xpath=//div[contains(text(),"Identifiants invalides.")]
