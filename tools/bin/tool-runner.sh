#!/usr/bin/env sh

set -e

err(){ >&2 echo "$@"; }

script_path="$(realpath "${0}")"
script_dir="$(dirname "$script_path")"
root_dir="$(realpath "$script_dir/../../")"

tool_name="$1"
if [ -z  "$tool_name" ]; then
    err "Missing required argument: tool"
    err "$0 tool [bin]"
fi

bin="$2"
if [ -z "$bin" ]; then
    bin="vendor/bin/${tool_name}"
fi

tool_path="${root_dir}/tools/${tool_name}";
tool_bin="${tool_path}/${bin}"

if [ ! -f "${tool_bin}" ]; then
    err "Installing ${tool_name}"
    composer install --working-dir="${tool_path}" >&2
fi

echo "${tool_bin}"
