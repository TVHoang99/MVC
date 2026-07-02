# MVC Framework - Abstract Class Model (UML)

## Class Diagram

```mermaid
classDiagram
    direction TB

    class Model {
        <<abstract>>
        +getProperties() array
    }

    class ResourceInterface {
        <<interface>>
        +_init($table, $id, $model)
        +save($model) bool
        +delete($model) bool
    }

    class Resource {
        -string $table
        -int $id
        -Model $model
        +_init($table, $id, $model)
        +save($model) bool
        +find($id) Model
        +all($model) array
        +delete($model) bool
    }

    class Task {
        <<entity>>
        #int $id
        #string $title
        #string $description
        +__set($name, $value)
        +__get($name)
    }

    class TaskResource {
        +__construct($table, $id, Task $task)
    }

    class TaskRepository {
        #TaskResource $taskResource
        +__construct()
        +add($model) bool
        +update($model) bool
        +get($id) Task
        +getAll($model) array
        +delete($model) bool
    }

    class Controller {
        <<abstract>>
        +array $vars
        +string $layout
        +set($d)
        +render($filename)
        #secure_input($data) string
        #secure_form($form) array
    }

    class TasksController {
        -TaskRepository $taskRepo
        +__construct()
        +index()
        +create()
        +edit($id)
        +delete($id)
    }

    class Dispatcher {
        -Request $request
        +dispatch()
        +loadController()
    }

    class Router {
        +parse($url, $request)$ 
    }

    class Request {
        +string $url
        +string $controller
        +string $action
        +array $params
        +__construct()
    }

    class Database {
        -PDO $bdd$
        +getBdd()$ PDO
    }

    Model <|-- Task : extends
    ResourceInterface <|.. Resource : implements
    Resource <|-- TaskResource : extends
    Controller <|-- TasksController : extends

    TaskResource --> Task : uses
    TaskRepository --> TaskResource : creates
    TasksController --> TaskRepository : uses
    TasksController --> Task : creates
    Resource --> Database : calls
    Dispatcher --> Router : calls
    Dispatcher --> Request : creates
```

---

## Abstract Class Specifications

### 1. Model (Abstract Base)

```
┌─────────────────────────────────────────┐
│           «abstract»                    │
│              Model                      │
├─────────────────────────────────────────┤
│  +getProperties(): array                │
└─────────────────────────────────────────┘
        △
        │ extends
┌───────┴─────────────────────────────────┐
│             «entity»                    │
│              Task                       │
├─────────────────────────────────────────┤
│  #id: int                               │
│  #title: string                         │
│  #description: string                   │
├─────────────────────────────────────────┤
│  +__set($name, $value)                  │
│  +__get($name)                          │
└─────────────────────────────────────────┘
```

| Aspect | Details |
|--------|---------|
| **Purpose** | Base class for all domain entities |
| **Key Method** | `getProperties()` - Reflects object state |
| **Pattern** | Template Method (provides base, children extend) |
| **Visibility** | `protected` properties for inheritance |

---

### 2. ResourceInterface (Contract)

```
┌─────────────────────────────────────────┐
│         «interface»                     │
│       ResourceInterface                 │
├─────────────────────────────────────────┤
│  +_init($table, $id, $model)            │
│  +save($model): bool                    │
│  +delete($model): bool                  │
└─────────────────────────────────────────┘
        △
        │ implements
┌───────┴─────────────────────────────────┐
│              Resource                   │
├─────────────────────────────────────────┤
│  -table: string                         │
│  -id: int                               │
│  -model: Model                          │
├─────────────────────────────────────────┤
│  +_init($table, $id, $model)            │
│  +save($model): bool                    │
│  +find($id): Model                      │
│  +all($model): array                    │
│  +delete($model): bool                  │
└─────────────────────────────────────────┘
        △
        │ extends
┌───────┴─────────────────────────────────┐
│          TaskResource                   │
├─────────────────────────────────────────┤
│  +__construct($table, $id, Task)        │
└─────────────────────────────────────────┘
```

| Aspect | Details |
|--------|---------|
| **Pattern** | Strategy Pattern (interface defines algorithm) |
| **Contract** | `_init`, `save`, `delete` |
| **Extension** | `find`, `all` added in concrete `Resource` |
| **Benefit** | Loose coupling, testability |

---

### 3. Controller (Abstract Base)

```
┌─────────────────────────────────────────┐
│           «abstract»                    │
│           Controller                   │
├─────────────────────────────────────────┤
│  +vars: array = []                      │
│  +layout: string = "default"            │
├─────────────────────────────────────────┤
│  +set($d)                               │
│  +render($filename)                     │
│  #secure_input($data): string           │
│  #secure_form($form): array             │
└─────────────────────────────────────────┘
        △
        │ extends
┌───────┴─────────────────────────────────┐
│        TasksController                  │
├─────────────────────────────────────────┤
│  -taskRepo: TaskRepository              │
├─────────────────────────────────────────┤
│  +__construct()                         │
│  +index()                               │
│  +create()                              │
│  +edit($id)                             │
│  +delete($id)                           │
└─────────────────────────────────────────┘
```

| Aspect | Details |
|--------|---------|
| **Purpose** | Base for all controllers |
| **Key Pattern** | Template Method (`render()` auto-resolves views) |
| **Security** | `#secure_input` - XSS prevention |
| **Security** | `#secure_form` - Batch sanitization |

---

## Interface vs Abstract Class Comparison

| Feature | ResourceInterface | Controller (Abstract) | Model (Abstract) |
|---------|-------------------|----------------------|------------------|
| **Type** | Interface | Abstract Class | Abstract Class |
| **Purpose** | Define contract | Provide base impl | Provide base impl |
| **Properties** | None | Yes | None |
| **Methods** | Signature only | Full implementation | Full implementation |
| **Multiple** | Can implement many | Can extend one | Can extend one |
| **Usage** | Resource, TaskResource | TasksController | Task |

---

## Design Patterns Summary

| Pattern | Where Used | Purpose |
|---------|------------|---------|
| **Repository** | TaskRepository | Abstracts data access |
| **Active Record** | Task (via Resource) | Object-relational mapping |
| **Strategy** | ResourceInterface | Algorithm encapsulation |
| **Singleton** | Database | Single connection instance |
| **Template Method** | Controller::render() | View rendering algorithm |
| **Factory** | Dispatcher::loadController() | Dynamic class instantiation |

---

## Inheritance Chain

```
ResourceInterface
        │
        ▼
    Resource
        │
        ▼
  TaskResource
        │
        ▼ (creates)
  TaskRepository ─────────────────────┐
                                      │
                                      ▼
Model ──► Task    Controller ──► TasksController
                    │                    │
                    │                    ▼
                    │              TaskRepository
                    │
                    ▼
              Dispatcher
                    │
                    ▼
               Request ◄── Router
```

---

## Visibility Matrix

| Class | Properties | Methods | Access Level |
|-------|------------|---------|--------------|
| **Model** | - | `getProperties()` | public |
| **Task** | `$id, $title, $description` | `__set, __get` | protected |
| **ResourceInterface** | - | `_init, save, delete` | public |
| **Resource** | `$table, $id, $model` | all | public |
| **TaskResource** | (inherited) | `__construct` | public |
| **TaskRepository** | `$taskResource` | CRUD methods | protected |
| **Controller** | `$vars, $layout` | render, set, secure | public |
| **TasksController** | `$taskRepo` | CRUD actions | private |

---

## Abstract Class Responsibilities

| Abstract Class | Responsibility | Children |
|----------------|----------------|----------|
| **Model** | Entity state management | Task |
| **ResourceInterface** | Data access contract | Resource |
| **Controller** | HTTP response handling | TasksController |

---

## Dependency Injection Points

```
TasksController
    ├── TaskRepository (injected in __construct)
    │       └── TaskResource (injected in __construct)
    │               └── Task (injected in __construct)
    └── Task (created per action)
```

| Injection | Method | Type |
|-----------|--------|------|
| TaskRepository → TaskResource | Constructor | Creation |
| TaskResource → Task | Constructor | Creation |
| TasksController → TaskRepository | Constructor | Creation |
