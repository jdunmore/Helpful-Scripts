#!/bin/bash
FILE=$1
echo "Starting to process $FILE at $(date)"

FOUNDSIZE=0
NOTFOUNDCOUNT=0
FILESPROCESSED=0

while read p; do
>---FILESPROCESSED=$[$FILESPROCESSED +1] 

>---if [ $(($FILESPROCESSED % 100)) == 0 ];
>---then
    >---echo -ne "$(date): Files processed $FILESPROCESSED \r"
    fi  

    # make filepath a variable so we can append full paths here if needed
>---FILEPATH="$p"
>---if [ -f $FILEPATH ];
>---then
>---    SIZE=$(du -H "$FILEPATH" | awk '{print $1}')
    >---let "FOUNDSIZE=$FOUNDSIZE + $SIZE"
    else
    >---let "NOTFOUNDCOUNT += 1"
    fi  
done < $FILE

echo -ne "\n"
echo "found files size: $FOUNDSIZE did not find $NOTFOUNDCOUNT files"
