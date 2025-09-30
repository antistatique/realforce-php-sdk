## Submitting issues

Please read the below before posting an issue. Thank you!

- If your question is about the Realforce API itself, please check out the [Realforce Doc](https://github.com/realforce/documentation). This project doesn't handle any of that logic - we're just helping you form the requests.

If, however, you think you've found a bug, or would like to discuss a change or improvement, feel free to raise an issue and we'll figure it out between us.

## Pull requests

This is a fairly simple wrapper, but it has been made much better by contributions from those using it. If you'd like to suggest an improvement, please raise an issue to discuss it before making your pull request.

Pull requests for bugs are more than welcome - please explain the bug you're trying to fix in the message.

There are a fair amount of PHPUnit unit tests. Unit testing against an API is a bit tricky, but I'd welcome any contributions to this. It would be great to have more test coverage.

## Developing

## 🚔 Check Symfony coding standards & best practices

You need to run composer before using [FriendsOfPHP/PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer).

### Command Line Usage

Check & Fix Symfony coding standards:

```bash
./vendor/bin/php-cs-fixer fix -v --dry-run --using-cache=no
```

Automatically fix coding standards

```bash
./vendor/bin/php-cs-fixer fix -v --using-cache=no
```

### Enforce code standards with git hooks

Maintaining code quality by adding the custom post-commit hook to yours.

```bash
cat ./bin/post-commit >> ./.git/hooks/post-commit
```
