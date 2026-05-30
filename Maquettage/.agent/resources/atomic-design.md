# Atomic Design & Manifest Rules

This document serves as the **Single Source of Truth** for UI Component definitions and structure within this project.

## 1. Definitions

We follow a strict **Atomic Design** methodology:

### ⚛️ Atom
- **Definition**: The smallest indivisible building block. Styles and basic structure.
- **Dependency**: None (usually).
- **Examples**: `Button`, `Badge`, `Avatar`, `Input`, `Icon`.

### 🔗 Molecule
- **Definition**: A group of Atoms functioning together as a unit.
- **Dependency**: Must use/contain Atoms.
- **Examples**: `Form Group` (Label+Input+Error), `Search Input` (Input+Icon), `Alert`.

### 🧩 Component (Organism)
- **Definition**: A complex, autonomous functional section of an interface. Composed of Molecules and/or Atoms.
- **Dependency**: Must use/contain Molecules and Atoms.
- **Examples**: `NavBar`, `Sidebar`, `ArticleTable` (with Pagination+Rows), `LoginForm`.

---

## 2. Manifest Structure

To maintain clarity, we split the component registry into three specialized manifests located in `ui-kit/`:

### `ui-kit/atoms-manifest.yaml`
- Contains strictly Atoms.
### `ui-kit/molecules-manifest.yaml`
- Contains strictly Molecules.
### `ui-kit/components-manifest.yaml`
- Contains Organisms/Components/Layouts.

#### Shared Format
```yaml
items:
  - name: "ComponentName"
    category: "CategoryName (e.g., Forms, Navigation)"
    path: "./category/component.html"
    status: "pending | validated"
    description: "Short description."
    documentation: "URL to official source (e.g., Preline UI)"
    dependencies: 
       - "AtomName"
       - "MoleculeName"
```

---

## 3. Linking & Documentation Rules (CRITICAL)

### Rule #1: Upstream Linking
- **Molecules** MUST explicitly link to the **Atoms** they are built with.
- **Components** MUST explicitly link to the **Molecules** and **Atoms** they use.

### Rule #2: Documentation Link
- **EVERY** item (Atom, Molecule, Component) MUST reference its official documentation source (e.g., Preline UI link) in its:
    1.  Manifest entry (`documentation:` field).
    2.  HTML file (Visible link).


### Rule #3: Autonomy
- **Components** must be "Autonomous Functional Units". They should be able to function independently in a preview context.