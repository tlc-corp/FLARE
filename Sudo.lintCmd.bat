echo.
echo                SUDO COMMAND
echo              FOR FLARE SERVERS
echo.
echo [TYPE ./flare-kill TO FORCEFULLY SHUT DOWN THE SERVER IF IT IS FROZEN]
echo.
goto :loop
:loop
echo \r//n\/r/[FLARE] >>
set /p cmd=""
if %cmd%="./flare-kill"
%cmd%
goto :loop
