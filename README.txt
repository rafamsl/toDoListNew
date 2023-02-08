>>> ToDo Entity <<<

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $status = 1;
    (1 = Created, 2 = Done, 3 = Expired)

    #[ORM\Column]
    private ?bool $pinned = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deadline = null;


>>> Routes <<<


1. Retrieve full list of todo tasks ordered by pinned status
@Route '/api/getall'
@Query params
 -> 'after'/'before': Filter by date
 -> 'title' : Filter by title
 -> 'sortBy' && 'direction': Sort by given collumn
 -> 'page' && 'limit' : Add pagination
 -> 'status' : Filter by status (ex: status = 2 brings all ToDos that were done)


2. Retrieve one todo by ID
@Route '/api/getone/{id<\d+>}'

3. Create a todo
@Route /api/createone
@Body JSON:
{
    "title":"sample todo expired",
    "description":"sample description"
    "pinned":true,
    "deadline" : "2023-02-05T18:25:43.511Z"
}

4. Edit TODO
@Route ''/api/editone/{id<\d+>}''
@Body JSON (All Optional):
{
    "title":"sample todo expired",
    "description":"sample description"
    "pinned":true,
    "deadline" : "2023-02-05T18:25:43.511Z"
}

5. Remove TODO
@Route '/api/removeone/{id<\d+>}'

6. Finish TODO by moving it to status 2
@Route '/api/finishone/{id<\d+>}'

7. Move all expired TODOs (deadline passed) to status 3
@Route '/api/expiretodos'

>>> Commands <<<

@Command symfony console doctrine:fixtures:load
-> Cleans ToDo table and add 10 sample todos using faker