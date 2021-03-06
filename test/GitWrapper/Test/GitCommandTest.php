<?php

namespace GitWrapper\Test;

use GitWrapper\GitCommand;

class GitCommandTest extends GitWrapperTestCase
{
    public function testCommand()
    {
        $command = $this->randomString();
        $argument = $this->randomString();
        $flag = $this->randomString();
        $option_name = $this->randomString();
        $option_value = $this->randomString();

        $git = GitCommand::getInstance($command)
            ->addArgument($argument)
            ->setFlag($flag)
            ->setOption($option_name, $option_value);

        $expected = "$command --$flag --$option_name='$option_value' '$argument'";
        $command_line = $git->getCommandLine();

        $this->assertEquals($expected, $command_line);
    }

    public function testOption()
    {
        $option_name = $this->randomString();
        $option_value = $this->randomString();

        $git = GitCommand::getInstance()
            ->setOption($option_name, $option_value);

        $this->assertEquals($option_value, $git->getOption($option_name));

        $git->unsetOption($option_name);
        $this->assertNull($git->getOption($option_name));
    }

    /**
     * @see https://github.com/cpliakas/git-wrapper/issues/50
     */
    public function testMultiOption()
    {
        $git = GitCommand::getInstance('test-command')
            ->setOption('test-arg', array(true, true));

        $expected = 'test-command --test-arg --test-arg';
        $command_line = $git->getCommandLine();

        $this->assertEquals($expected, $command_line);
    }
}
