@verbatim
final class UserAuthorizer extends Authorizer
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
@endverbatim
