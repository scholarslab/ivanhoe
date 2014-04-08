#!/usr/bin/env python


"""\
This deploys this Ivanhoe theme to Heroku. BRANCH is optional and defaults to
'develop'.
"""


from __future__ import unicode_literals, print_function

import argparse
from contextlib import contextmanager
import datetime
import os
import shutil
import subprocess
import sys
import tempfile


## defaults
BRANCH = 'develop'
REPO   = 'git@heroku.com:ivanhoe-staging.git'


def git(command, *args):
    subprocess.call(['git', command] + list(args))


def cleanup(basedir):
    shutil.rmtree(basedir, ignore_errors=True)


def parse_args(argv):
    ap = argparse.ArgumentParser(description=__doc__)

    ap.add_argument('-a', '--app-repo', dest='app_repo', metavar='APP_REPO',
                    default=REPO,
                    help='The repository to deploy to. Default is ' + REPO)
    ap.add_argument('-b', '--branch', dest='branch', metavar='BRANCH',
                    default=BRANCH,
                    help='The branch to deploy. Default is {}.'.format(BRANCH))

    args = ap.parse_args(argv)
    return args


@contextmanager
def lcd(dirname):
    cwd = os.getcwdu()
    os.chdir(dirname)
    try:
        yield
    finally:
        os.chdir(cwd)


@contextmanager
def tempdir():
    td = tempfile.mkdtemp()
    try:
        yield td
    finally:
        cleanup(td)


def main(argv=None):
    args    = parse_args(argv if argv is not None else sys.argv[1:])

    with tempdir() as tmp:
        wpdir   = os.path.join(tmp, 'wordpress')
        ivanhoe = os.path.join(wpdir, 'wp-content', 'themes', 'ivanhoe')
        now     = datetime.datetime.now()

        git('clone', '--recursive', args.app_repo, wpdir)
        with lcd(ivanhoe):
            git('checkout', args.branch)
            git('pull')
        with lcd(wpdir):
            git('add', '.')
            git('commit', '-m', 'Deploy {}'.format(now.strftime('%c')))
            git('push')


if __name__ == '__main__':
    main()
