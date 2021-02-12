trap on_sigchld SIGCHLD
on_sigchld() { sleep 1; }

while :; do sleep 100; done
