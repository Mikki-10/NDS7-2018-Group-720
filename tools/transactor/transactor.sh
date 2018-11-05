function getAccounts() {
    for dir in /gethdata/*; do
        echo $dir/
    done
}

getAccounts
