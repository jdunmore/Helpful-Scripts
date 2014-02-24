#!/bin/bash
while read p; do
    du -H "$p"
done < input.file |  awk '{i+=$1} END {print i}'

