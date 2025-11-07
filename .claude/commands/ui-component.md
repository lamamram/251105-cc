---
description: Create a UI Component in /components/ui
argument-hint: Component name | Component summary
---

## Context

Parse $ARGUMENTS to get the folowing values:

- [name]: Component name from $ARGUMENTS, converted to PascalCase
- [summary]: Component summary from $ARGUMENTS 

## Task

Make a UI commponent according to the [name] and [summary] provided, following the guidelines:

- Create the component file in __dir__/[name]/[name].tsx
- Use a functional component with the name [name]
- Reference the [summary] when making the component

## Variants

- Add the following variants for the component using the colours from the themes variables in the @__global_css__ file:

  1. primary
  2. secondary
  3. success
  4. danger
  5. warning

- Support common patterns like diabled states and sizes where appropriate (sm, md, lg, defaulting to md when preference is passed

## Testing

- Make a test file for the component to test basic use cases
- Use @__compoent_dir__\Component.test.tsx as a reference to make the test file for the new component
- Run tests and iterate until they all pass

## Preview

- DO NOT add the component to any page yet