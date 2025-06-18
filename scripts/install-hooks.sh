#!/bin/sh

HOOKS_DIR=$(git rev-parse --show-toplevel)/hooks
GIT_HOOKS_DIR=$(git rev-parse --git-path hooks)

echo "Installing Git hooks from $HOOKS_DIR to $GIT_HOOKS_DIR..."

for hook in $HOOKS_DIR/*; do
  name=$(basename $hook)
  ln -sf "$HOOKS_DIR/$name" "$GIT_HOOKS_DIR/$name"
  chmod +x "$GIT_HOOKS_DIR/$name"
done

echo "Git hooks installed."
