#!/usr/bin/env bash

if [ $# -eq 0 ]; then
  echo "Missing version number";
  exit 1;
fi

cp LICENSE README.md wp-etransaction

zip ca-etransaction-${1}.zip  ca-etransaction  -x "*/.git" -x "*/.gitignore" -r
rm ca-etransaction/{README.md,LICENSE}


