#!/usr/bin/env bash

if [ $# -eq 0 ]; then
  echo "Missing version number";
  exit 1;
fi

zip wp-etransaction-${1}.zip  wp-etransaction  -x "*/.git" -x "*/.gitignore" -r

